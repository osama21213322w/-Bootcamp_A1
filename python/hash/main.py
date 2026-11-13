import hashlib
import string
import secrets
import random

chars = string.ascii_letters + string.digits + string.punctuation


def make_password(length):
    password = ""
    for i in range(length):
        password += secrets.choice(chars)
    return password
def sha256(text):
    return hashlib.sha256(text.encode()).hexdigest()
def main():
    print("Password Generator & SHA-256 Hasher")

    length = input("password length: ")
    if not length.isdigit():
        print("enter a number")
        return

    length = int(length)
    if length < 4:
        print("too short")
        return

    password = make_password(length)
    print("password:", password)
    print("sha256  :", sha256(password))

    text = input("text hash: ")
    print("sha256  :", sha256(text))


main()
