drop table if exists table_a;
create table table_a
(
    id       INTEGER not null
        primary key autoincrement
        unique,
    column_a TEXT,
    column_b TEXT,
    column_c TEXT
);

INSERT INTO table_a (id, column_a, column_b, column_c) VALUES (1, 'Plain text A', 'Plain text B', 'Plain text C');
INSERT INTO table_a (id, column_a, column_b, column_c) VALUES (2, '[{$varA}]', '[{$varB}]', '[{$varC}]');
INSERT INTO table_a (id, column_a, column_b, column_c) VALUES (3, '[{$varA}]', null, '[{$varC}]');

drop table if exists table_b;
create table table_b
(
    id       INTEGER not null
        primary key autoincrement
        unique,
    column_a TEXT,
    column_b TEXT,
    column_c TEXT
);

INSERT INTO table_b (id, column_a, column_b, column_c) VALUES (2, '[{$varA}]', '[{$varB}]', '[{$varC}]');
