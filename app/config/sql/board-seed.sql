--
-- Seed board database
--
USE board;

--
-- thread table
--
INSERT INTO thread SET title='Hello', created=NOW();
INSERT INTO thread SET title='thread 2', created=NOW();

--
-- comment table
--
INSERT INTO comment SET thread_id=1, username='sakana-san', body='I am hungry', created=NOW();
INSERT INTO comment SET thread_id=1, username='Yuigahama Yui', body='Yahallo!', created=NOW();
INSERT INTO comment SET thread_id=2, username='Asuka', body='Guten morgen!', created=NOW();
