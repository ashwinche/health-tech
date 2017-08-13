#!/usr/bin/python

import MySQLdb
import email

db = MySQLdb.connect(host="localhost",           # your host, usually localhost
                     user="ec2-user",            # your username
                     passwd="",                  # your password
                     db="HealthTechSchema")      # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()

def create_email(params):
  query = """insert into emails
    (user_id, subject, user_email, content, nature, status, times_sent) values
    ()
  """.substitute(**params)


# Use all the SQL you like


cur.execute("""
    insert into emails
    (user_id, subject, user_email, content, nature, status, times_sent) values
    ('163', 'subject line', 'ashwinchetty@gmail.com', '<html><body>Email <i>contents</i>.</body></html>','update','queued','0')
  """)

cur.execute("SELECT * FROM emails where status='queued' limit 1")


# print all the first cell of all the rows
for row in cur.fetchall():
  print row

db.close()
