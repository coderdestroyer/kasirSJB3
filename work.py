import time
import mysql.connector
import random
import instaloader
import os
import redis

L = instaloader.Instaloader()

# Koneksi ke Redis dan MySQL
r = redis.Redis(host='8.222.247.250', port=6379, db=0)

db = mysql.connector.connect(
    host="8.222.247.250",
    user="fdb",
    password="Tanpanama123!!##",
    database="face_database"
)
cursor = db.cursor()

base_folder = r'C:/'

def update_status(username):
    cursor.execute("UPDATE account_list SET crawl = 'downloaded' WHERE username = %s", (username,))
    db.commit()

while True:
    target_user = r.brpop("user_tasks")  # Ambil tugas dari antrian
    if target_user:
        target_user = target_user[1].decode("utf-8")
        print(f"Worker sedang memproses {target_user}")

        try:
            profile = instaloader.Profile.from_username(L.context, target_user)

            status = 'public' if not profile.is_private else 'private'
            followers = profile.followers
            following = profile.followees
            name = profile.full_name
            bio = profile.biography

            cursor.execute("""
                UPDATE account_list 
                SET status = %s, followers = %s, following = %s, name = %s, bio = %s 
                WHERE username = %s
            """, (status, followers, following, name, bio, target_user))
            db.commit()

            if profile.is_private or profile.followers > 12000 or profile.followers < 5 or profile.mediacount == 0:
                print(f"Skipping {target_user}: Account does not meet criteria.")
                cursor.execute("UPDATE account_list SET crawl = 'skipped' WHERE username = %s", (target_user,))
                db.commit()
                continue

            print(f"Mengunduh foto dari akun: {target_user}")
            user_folder = os.path.join(base_folder, target_user)
            os.makedirs(user_folder, exist_ok=True)
            post_count = profile.mediacount
            count = 0

            for i, post in enumerate(profile.get_posts()):
                if post.is_video:
                    continue
                
                if post_count > 50 and i % 2 != 0:
                    continue 
                
                post_date = post.date_utc.strftime("%Y-%m-%d")
                filename = f"{post.shortcode}_{post_date}"
                file_path = os.path.join(user_folder, filename)
                
                L.download_pic(file_path, post.url, post.date_utc)
                
                count += 1
                if count >= 20:
                    break

            update_status(target_user)
            print(f"Selesai mengunduh untuk akun: {target_user}\n")

        except instaloader.exceptions.ProfileNotExistsException:
            print(f"Profile {target_user} does not exist.")
            cursor.execute("UPDATE account_list SET crawl = 'not_found' WHERE username = %s", (target_user,))
            db.commit()
        
        except Exception as e:
            print(f"Could not access following list for {target_user}: {e}")
        
        sleep_duration = random.uniform(50, 80)
        print(f"Sleeping for {sleep_duration:.2f} seconds...")
        time.sleep(sleep_duration)

cursor.close()
db.close()
