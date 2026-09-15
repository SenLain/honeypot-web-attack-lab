# Honeypot & Web Attack Analysis Lab

⚠️ **Disclaimer:** This project is an intentionally vulnerable application built for
security research and education. **Never expose it to the public internet.**
It contains deliberate XSS, LFI, and SQLi vulnerabilities.

> *Originally developed as a group project during my Applied Computer Science
> degree at Howest. This repository is maintained by me as a portfolio piece*

## Overview

A honeypot environment that simulates a vulnerable PHP web application to study
attacker behaviour. The stack includes:

- A deliberately vulnerable web app exposing **XSS**, **LFI**, and **SQLi**
- **Cowrie** Telnet honeypot (port 2223) for capturing shell-based attacks
- **NGINX** as the web server, hardened with security headers and ModSecurity WAF
- **Elastic Stack (Filebeat → Elasticsearch → Kibana)** for log aggregation and
  attack visualization
- A **decoy database** with fake users/products to mislead attackers, kept
  separate from the real application database

Session management, user interaction tracking, and decoy services are included
to increase the realism of the environment.

## Architecture

```
Client
  │
  ▼
NGINX (+ ModSecurity WAF)
  │
  ▼
PHP Application ──► SQLite (website.db — real users)
  │              └─► SQLite (database.db — decoy data)
  │
  ▼
Filebeat
  │
  ▼
Elasticsearch ──► Kibana (dashboards)

Cowrie (telnet :2223) ──► JSON logs ──► Filebeat
```
## Key Takeaways

- Hands-on experience deploying and tuning a WAF (ModSecurity) and interpreting
  its alerts alongside application logs
- Practical understanding of how XSS, LFI, and SQLi payloads appear in logs and
  how attackers chain them
- Building a log pipeline from scratch (Filebeat → Elasticsearch → Kibana) and
  designing dashboards for attack triage
- First-hand incident response: an attacker exploited an insecure avatar upload
  to achieve RCE (see **Real Attack Observed** below)
- Lessons in operational security: isolating the honeypot, using decoy data, and
  never exposing real credentials

## Real Attack Observed

During the project, the environment was attacked (including by course lecturers
as a test). Attack payloads consistently contained the marker `k0enk_was_here`
and targeted URLs, XSS, IDOR, and SQLi endpoints.

**Critical finding:** Our avatar upload was not validating file contents. An
attacker uploaded a `.php` file containing a shell, achieving **Remote Code
Execution (RCE)**. We discovered the file in the avatars directory, deleted it
immediately, and hardened the upload handler with three checks:

```php
// Check file size
if ($file_size > $max_file_size) {
    echo '<div class="message error">File size exceeds the maximum limit of 2MB.</div>';
    exit;
}

// Check file extension
if (!in_array($file_ext, $allowed_extensions)) {
    echo '<div class="message error">Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.</div>';
    exit;
}

// Check if the file is a valid image
if (!getimagesize($file_tmp)) {
    echo '<div class="message error">The uploaded file is not a valid image.</div>';
    exit;
}
```

## Secure Web Environment

### Configuration

- **Security headers enforced:**
  - `Content-Security-Policy`
  - `X-Frame-Options`
  - `X-Content-Type-Options`
- **HTTPS** via self-signed certificate
- **Secure avatar upload** — uploaded files can no longer be executed as scripts
- **HTTP methods restricted** to `GET` and `POST`

### Environment

- Secure login and admin panel
- Two separate databases:
  - `website.db` — legitimate user data
  - `database.db` — decoy data for attackers
- Input sanitization and validation in place for most forms
- The three vulnerabilities (SQLi, XSS, LFI) are **intentionally left in** to
  keep attackers engaged

## Installation & Setup

### Requirements

- **OS:** Debian-based (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS)
- **Privileges:** Root
- NGINX
- Filebeat
- ModSecurity (with the NGINX connector)
- PHP ≥ 8.2
- sqlite3
- Git
- Cowrie (Telnet honeypot)

### Configuration

1. **NGINX virtual host** — import the config from
   [`./config_files/nginx/default.conf`](./config_files/nginx/default.conf).

2. **Self-signed certificate** — generate one with:
   ```bash
   sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
     -keyout /etc/ssl/private/nginx-selfsigned.key \
     -out /etc/ssl/certs/nginx-selfsigned.crt
   ```

3. **Load the ModSecurity module** in `nginx.conf` (full file at
   [`./config_files/nginx/nginx.conf`](./config_files/nginx/nginx.conf)):
   ```
   load_module modules/ngx_http_modsecurity_module.so;
   ```

4. **Filebeat output** — edit
   [`./config_files/filebeat/filebeat.yml`](./config_files/filebeat/filebeat.yml)
   and point it at your own Elasticsearch instance:
   ```yaml
   output.elasticsearch:
     hosts: ["<your-elk-host>:9200"]
     preset: balanced
     protocol: "https"
     username: "<username>"
     password: "<password>"
     ssl:
       enabled: true
       ca_trusted_fingerprint: "<your-ca-fingerprint>"
       verification_mode: "none"
     index: "honeypot-filebeat"
     setup.template.name: "honeypot-filebeat"
     setup.template.pattern: "honeypot-filebeat"
   ```

### Deploying the Web App

Clone the [`html`](./html) directory (or download the source). The provided
NGINX config expects the app to live at `/usr/share/nginx/html`.

### Databases

The honeypot uses two SQLite databases:

- `website.db` — the real application database (real users)
- `database.db` — a **decoy** database with fake users and products, so
  attackers believe they have successfully exfiltrated data

Set them up with the provided script:

```bash
chmod +x create_database.sh
./create_database.sh
```

The script:

- Creates `website.db` and `database.db`
- Populates them from the `database-dump/` folder
- Adds an admin user with **default demo credentials**:
  ```
  admin:ThisIsTheM0stSecureServerEver
  ```
  *(Change these in any non-lab deployment.)*

After running the script, you can delete the `database-dump/` directory and
`create_database.sh`.

## Challenges & Solutions

The three intentionally vulnerable endpoints each have a reference solution in
the [`solutions/`](./solutions) folder:

| Challenge | Vulnerability | Solution |
|-----------|---------------|----------|
| 1 | SQL Injection (SQLi) | [`solutions/SQLI.md`](./solutions/SQLI.md) |
| 2 | Cross-Site Scripting (XSS) | [`solutions/XSS.md`](./solutions/XSS.md) |
| 3 | Local File Inclusion (LFI) | [`solutions/LFI.md`](./solutions/LFI.md) |