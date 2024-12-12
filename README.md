# Web Security and Honeypot Project - Group 9

## Project Overview
This project is a honeypot designed to attract and analyze malicious activity by simulating a vulnerable web application. The project features a website environment with 3 vulnerabilities, including Cross-Site Scripting (XSS), Local File Inclusion (LFI), and SQL Injection (SQLi), alongside logging mechanisms using elastic stack and kibana dashboards, and also a telnet(port 2223) honeypot that is done using cowrie. It uses NGINX as the web server to host the application and is secured using in class taught principes such as security headers etc.

The primary objective is to study attacker behavior while safeguarding sensitive data and ensuring a robust and controlled deployment. Additionally, the project incorporates features such as session management, user interaction tracking, and decoy services to enhance the honeypot's realism.

## Installation and setup 

### Installation Requirements
 - Operating System: Debian-based system (Kali, Ubuntu, Kubuntu, Linux Mint, Zorin OS)
 - Privileges: Execute as root user
### Prerequisites 
[Here]() you will find setp-by-step guide on how to install everything
 - NGINX installed on the server.
 - Filebeat for sending log files
 - Modsecurity(with nginx plugin) as a WAF
 - PHP => 8.2
 - sqlite3
 - Git for cloning the repository.
 - cowrie telnet honeypot

 
### Config
First import the nginx virtual host configuration from [./config_files/nginx/default.conf](./config_files/nginx/default.conf)

Also create certificate using openssl
> `sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt`

In the nginx.conf load modsec modules (the full file can also be found at [./config_files/nginx/nginx.conf](./config_files/nginx/nginx.conf))
```
load_module modules/ngx_http_modsecurity_module.so;
```
In the filebeat.yml file update the data output ([./config_files/filebeat/filebeat.yml](./config_files/filebeat/filebeat.yml))
```
output.elasticsearch:
  # Array of hosts to connect to.
  hosts: ["elk.hp.technet.howest.be:9200"]

  # Performance preset - one of "balanced", "throughput", "scale",
  # "latency", or "custom".
  preset: balanced

  # Protocol - either `http` (default) or `https`.
  protocol: "https"

  # Authentication credentials - either API key or username/password.
  #api_key: "id:api_key"
  username: "group-09"
  password: "treflipcrook"
  ssl:
    enabled: true
    ca_trusted_fingerprint:"283B8D5987C5EE09280451F532CCFB2E1AB777ED2955FA5F1B6FD1ADA85AB67E"
    verification_mode: "none"
    index: "group-09-filebeat"
    setup.template.name: "group-09-filebeat"
    setup.template.pattern: "group-09-filebeat"

```

### Environment
Now you can use git to clone [html directory](./html) or just simply download all the source code. NOTE The [nginx config](./config_files/nginx/default.conf) provided uses `/usr/share/nginx/html` as a path to the environment.

### Database 
This honeypot uses 2 databases website.db that stores actual users and database.db, a fake database that stores fake users and products so that attackers can think that they successfully retrieve users data.

To setup them up simply run the bash script
```
chmod +x create_database.sh
./create_database.sh
```
The script will:

- Create website.db and database.db.
- Populate them with initial data from the database-dump/ folder.
 - Add admin user with credentials:  
admin:ThisIsTheM0stSecureServerEver

After that you can delete the 'database-dump' directory as well as the 'create_database.sh' script
