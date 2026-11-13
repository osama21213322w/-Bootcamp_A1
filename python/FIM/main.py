import hashlib
import time
import os

def ghash(p):
    h = hashlib.sha256()
    try:
        with open(p, "rb") as f:
            while True:
                c = f.read(4096)
                if not c:
                    break
                h.update(c)
        return h.hexdigest()
    except:
        return "err"

print("fim tool")
p = input("path: ").strip()

if os.path.exists(p):
    old = ghash(p)
    print("base:", old)
    print("watching...")
    
    while True:
        time.sleep(3)
        new = ghash(p)
        
        if new == "err":
            print("alert: file missing or locked")
            old = "err"
        elif new != old:
            print("alert: changed")
            print("old:", old)
            print("new:", new)
            old = new
else:
    print("not found")
