import socket
import ipaddress
import time
from concurrent.futures import ThreadPoolExecutor, as_completed

target = input("Enter IPv4 address: ").strip()

try:
    ipaddress.IPv4Address(target)
except ipaddress.AddressValueError:
    raise SystemExit("Invalid IPv4 address.")

def scan_port(port):
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(0.5)

    try:
        result = sock.connect_ex((target, port))
        return port, "Open" if result == 0 else "Closed"
    except OSError:
        return port, "Closed"
    finally:
        sock.close()

start_time = time.perf_counter()
results = []

print(f"Scanning {target}, ports 1-1024...")

with ThreadPoolExecutor(max_workers=50) as executor:
    futures = [
        executor.submit(scan_port, port)
        for port in range(1, 1025)
    ]

    for future in as_completed(futures):
        results.append(future.result())

results.sort()

print("\nOpen ports:")
open_ports = [port for port, status in results if status == "Open"]

if open_ports:
    for port in open_ports:
        print(f"Port {port}: Open")
else:
    print("No open ports.")

print("\nClosed ports:")
closed_ports = [port for port, status in results if status == "Closed"]

for port in closed_ports:
    print(f"Port {port}: Closed")

print(f"\nscan completed  {time.perf_counter() - start_time:.2f} seconds.")
