CREATE TABLE articles ( id integer not null constraint articles_pk primary key autoincrement, title varchar(250), author varchar(250), content text);
INSERT INTO "articles" ("title", "author", "content") VALUES
    ('TestTitle', 'Martins', 'Content for very first article'),
    ('Title1', 'Martins', 'Other article');