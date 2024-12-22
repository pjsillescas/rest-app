create table product(
	id integer auto_increment,
	name varchar(64),
	
	constraint product_PK primary key(id)
);

insert into product(id, name) values
	(1, 'product 1'),
	(2, 'product 2'),
	(3, 'product 3'),
	(4, 'product 4');
