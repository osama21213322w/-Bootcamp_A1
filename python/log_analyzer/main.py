import re

patterns = {
    "SQL Injection": re.compile(
        r"union(?:\s|%20|\+|/\*.*?\*/)+select|"
        r"select(?:\s|%20|\+).*?(?:\s|%20|\+)from|"
        r"(?:'|%27)\s*(?:or|and)\s*(?:'|%27)?\d+(?:'|%27)?\s*(?:=|%3d)\s*(?:'|%27)?\d+|"
        r"(?:'|%27)\s*(?:or|and)\s*(?:'|%27)?\w+(?:'|%27)?\s*(?:=|%3d)\s*(?:'|%27)?\w+",
        re.IGNORECASE,
    ),
    "Path Traversal": re.compile(
        r"\.\./|\.\.\\|%2e%2e%2f|%2e%2e%5c|%2e%2e/|\.\.%2f"
        r"|/etc/passwd|/etc/shadow|boot\.ini|win\.ini",
        re.IGNORECASE,
    ),
    "XSS": re.compile(
        r"<script[^>]*>|javascript:|onerror\s*=|onload\s*=|%3cscript",
        re.IGNORECASE,
    ),
}

ip_pattern = re.compile(r"\b(?:\d{1,3}\.){3}\d{1,3}\b")


def is_valid_ip(ip):
    for part in ip.split("."):
        if int(part) > 255:
            return False
    return True


def find_ip(line):
    for match in ip_pattern.finditer(line):
        ip = match.group()
        if is_valid_ip(ip):
            return ip
    return "Unknown"


def scan_file(path):
    total_lines = 0
    total_alerts = 0
    counts = {name: 0 for name in patterns}

    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            for num, line in enumerate(f, 1):
                total_lines += 1
                ip = find_ip(line)
                for name, pattern in patterns.items():
                    if pattern.search(line):
                        total_alerts += 1
                        counts[name] += 1
                        print(f"[ALERT] {name} | line {num} | ip {ip}")
    except FileNotFoundError:
        print(f"file not found: {path}")
        return
    except OSError as e:
        print(f"could not read file: {e}")
        return

    print()
    print("Scan complete")
    print(f"lines scanned : {total_lines}")
    print(f"alerts found  : {total_alerts}")
    for name, count in counts.items():
        print(f"  {name}: {count}")


def main():
    print("Log Analyzer")
    path = input("log file: ").strip()
    if not path:
        print("no file path given")
        return
    scan_file(path)


if __name__ == "__main__":
    main()
