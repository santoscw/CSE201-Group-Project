CREATE DATABASE DOG


USE DOG

CREATE TABLE state(
id					INT				PRIMARY KEY				IDENTITY,
state				VARCHAR(50)		NOT NULL
)


CREATE TABLE city(
id					INT				PRIMARY KEY				IDENTITY,
name				VARCHAR(50)		NOT NULL,
state_id			INT				FOREIGN KEY REFERENCES dbo.state(id)				
)


CREATE TABLE shelter(
id					INT				PRIMARY KEY				IDENTITY,
name				VARCHAR(50)		NOT NULL,
address				VARCHAR(50)		NOT NULL,
address2			VARCHAR(50)		,
city_id				INT				FOREIGN KEY REFERENCES dbo.city(id),
phone				VARCHAR(20)		NOT NULL,
Email				VARCHAR(50)		NOT NULL,
)


INSERT INTO state(state) VALUES ('Ohio')

INSERT INTO city(name,state_id) VALUES ('Oxford','1'),('Columbus','1')

INSERT INTO shelter(name,address,phone,email,city_id) VALUES 
('Great Lakes Giant Schnauzer Rescue','7021 Surrey Dr','Phone: 513-750-5895','Email: glgsr801@yahoo.com','3'),
('Max''s Animal Mission','7084 Ketch Harbour St','Phone: 614-284-1465','Email: maxsanimalmission@gmail.com','4')


CREATE TABLE section(
Id					INT				PRIMARY KEY				IDENTITY,
section				VARCHAR(50)		NOT NULL,
)
 

 CREATE TABLE country(
Id					INT				PRIMARY KEY				IDENTITY,
country				VARCHAR(50)		NOT NULL,
 )



CREATE TABLE dogbreeds(
id					INT				PRIMARY KEY				IDENTITY,
name				VARCHAR(50)		NOT NULL,		
section_id			INT				FOREIGN KEY REFERENCES dbo.section(Id),
country_id			INT				FOREIGN KEY REFERENCES dbo.country(Id),
image				VARCHAR(100),
) 

INSERT INTO section(section) VALUES ('Sheepdogs'),('Scent hounds')

INSERT INTO country(country) VALUES ('JAPAN'),('SPAIN')

INSERT INTO dogbreeds(name,section_id,country_id,image) VALUES
('WIREHAIRED SLOVAKIAN POINTER', '1','1','http://www.fci.be/Nomenclature/Illustrations/319g05.jpg'),
('MEDIUM-SIZED ANGLO-FRENCH HOUND','2','2','http://www.fci.be/Nomenclature/Illustrations/331g02.jpg')
