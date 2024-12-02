# Web Security and Honeypot Project - Group 9
# DISCLAIMER 
While we poured our hearts and souls into this project, the time prevented us from completing the documentation on time. Despite the challenges, we are proud of the work we have done and the knowledge we've gained. The code itself has comments that explain some stuff, but we know that's not enough and we will still be working on it and do everything we can do, to finish this journey when we have time - but it will not be finished utill 9 am 02.12.2024 😟. We apologize for being late.

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

After that you can delete the 'database-dump' directory as well as the 'create_database.sh' script


## S



## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/ee/gitlab-basics/add-file.html#add-a-file-using-the-command-line) or push an existing Git repository with the following command:

test
```
cd existing_repo
git remote add origin https://gitlab.ti.howest.be/ti/2024-2025/s3/web-security-and-honeypot/template.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://gitlab.ti.howest.be/ti/2024-2025/s3/web-security-and-honeypot/template/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Set auto-merge](https://docs.gitlab.com/ee/user/project/merge_requests/merge_when_pipeline_succeeds.html)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/index.html)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing (SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thanks to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README

Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
