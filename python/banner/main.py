
import socket

def grab(ip, port):
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.settimeout(3)
        s.connect((ip, port))

        if port in [80, 8080, 8000]:
            request = f"HEAD / HTTP/1.1\r\nHost: {ip}\r\nConnection: close\r\n\r\n"
            s.send(request.encode())

        try:
            data = s.recv(1024)
            banner = data.decode('utf-8', errors='ignore')

            if banner:
                print(f"port {port}: {banner.splitlines()[0]}")
            else:
                print(f"port {port}: no banner")

        except socket.timeout:
            print(f"port {port}: connected, no banner")

        s.close()

    except socket.timeout:
        print(f"port {port}: connection timeout")

    except ConnectionRefusedError:
        print(f"port {port}: connection refused")

    except OSError:
        print(f"port {port}: connection error")


ip = input("enter target ip: ")

ports = input("enter ports separated by comma: ")
ports = ports.split(",")

for port in ports:
    grab(ip, int(port))
