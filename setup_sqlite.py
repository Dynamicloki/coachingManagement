import sqlite3
import os
import hashlib

db_path = os.path.join(os.path.dirname(__file__), 'database.sqlite')
conn = sqlite3.connect(db_path)
cur = conn.cursor()

cur.execute('''
CREATE TABLE IF NOT EXISTS tbladmin (
    adminid INTEGER PRIMARY KEY AUTOINCREMENT,
    adminusername TEXT NOT NULL UNIQUE,
    adminpassword TEXT NOT NULL,
    addedDatetime TEXT,
    addedipaddress TEXT
)''')

cur.execute('''
CREATE TABLE IF NOT EXISTS tblsubject (
    subjectid INTEGER PRIMARY KEY AUTOINCREMENT,
    subjectname TEXT NOT NULL UNIQUE,
    status INTEGER DEFAULT 1,
    addedipaddress TEXT,
    addedDatetime TEXT
)''')

cur.execute('''
CREATE TABLE IF NOT EXISTS tblclass (
    classid INTEGER PRIMARY KEY AUTOINCREMENT,
    classname TEXT NOT NULL,
    subject TEXT,
    status INTEGER DEFAULT 1,
    addedDateTime TEXT,
    addedIPaddress TEXT
)''')

cur.execute('''
CREATE TABLE IF NOT EXISTS tblstudent2 (
    studentid INTEGER PRIMARY KEY AUTOINCREMENT,
    studentname TEXT NOT NULL,
    dob TEXT,
    class TEXT,
    season TEXT,
    studentmobile TEXT,
    schoolname TEXT,
    gender TEXT,
    studentmail TEXT,
    studentlogin TEXT,
    fathername TEXT,
    mothername TEXT,
    fathermobilenumber TEXT,
    motherMobileNumber TEXT,
    parantsmail TEXT,
    parentspassword TEXT,
    address TEXT,
    addedDateTime TEXT,
    addedipaddress TEXT
)''')

# Admin: admin / admin123
pwd_md5 = hashlib.md5(b'admin123').hexdigest()
cur.execute('INSERT OR IGNORE INTO tbladmin (adminusername, adminpassword, addedDatetime, addedipaddress) VALUES (?, ?, datetime("now"), ?)', ('admin', pwd_md5, '127.0.0.1'))

cur.executemany('INSERT OR IGNORE INTO tblsubject (subjectname, status, addedipaddress, addedDatetime) VALUES (?, ?, ?, datetime("now"))', [
    ('Mathematics', 1, '127.0.0.1'),
    ('Science', 1, '127.0.0.1'),
    ('English', 1, '127.0.0.1'),
    ('Social Studies', 1, '127.0.0.1')
])

cur.execute('INSERT OR IGNORE INTO tblclass (classname, subject, status, addedDateTime, addedIPaddress) VALUES (?, ?, ?, datetime("now"), ?)',
    ('Class 10', '1+2+3', 1, '127.0.0.1'))

cur.execute('''INSERT OR IGNORE INTO tblstudent2 (studentname, dob, class, season, studentmobile, schoolname, gender, studentmail, studentlogin, fathername, mothername, fathermobilenumber, motherMobileNumber, parantsmail, parentspassword, address)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)''',
    ('John Doe', '2005-01-15', '1', '2023-24', '9876543210', 'City High School', '1', 'student@test.com', 'student123', 'Robert Doe', 'Mary Doe', '9876543211', '9876543212', 'parent@test.com', 'parent123', '123 Main Street')
)

conn.commit()
print('SQLite Database created successfully at:', db_path)
cur.execute("SELECT name FROM sqlite_master WHERE type='table'")
print('Tables:', cur.fetchall())
conn.close()
