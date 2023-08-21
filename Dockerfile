FROM ubuntu:16.04

COPY src /tmp/src
COPY deploy /tmp/deploy

RUN apt-get update
RUN apt-get install -y software-properties-common

RUN apt-add-repository -y ppa:ansible/ansible
RUN apt-get update
RUN apt-get install -y ansible
RUN apt-get install -y sudo

#RUN ansible-playbook /tmp/deploy/main.yml -i /tmp/deploy/hosts.yml

CMD date >> /start_log.txt; tail -f /dev/null
