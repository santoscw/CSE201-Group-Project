CREATE DATABASE DOG


USE DOG

--==================================================================================================shelter databse
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
active				TINYINT			NOT NULL,
name				VARCHAR(50)		NOT NULL,
address				VARCHAR(50)		NOT NULL,
address2			VARCHAR(50)		,
city_id				INT				FOREIGN KEY REFERENCES dbo.city(id),
phone				VARCHAR(50)		NOT NULL,
Email				VARCHAR(50)		NOT NULL,
)

--==========================================Insert Value into State
SET IDENTITY_INSERT [state] ON;
INSERT INTO state(id,state) VALUES ('1','Ohio'),
								   ('2','Indiana')
SET IDENTITY_INSERT [state] OFF

--==========================================Insert Value into city
SET IDENTITY_INSERT [city] ON;
INSERT INTO city(id,name,state_id) VALUES ('1','Oxford','1'),
										  ('2','Columbus','1'),
										  ('3','Campbell','1'),
										  ('4','Nelsonville','1'),
										  ('5','Strongsville','1'),
										  ('6','Urbana','1'),
										  ('7','Logansport','2'),
										  ('8','Clinton','2'),
										  ('9','Dunkirk','2'),
										  ('10','Jasonville','2')
SET IDENTITY_INSERT [city] OFF
--==========================================Insert Value into shelter
SET IDENTITY_INSERT [shelter] ON;
INSERT INTO shelter(id, active,name,address,city_id,phone,Email) VALUES 
('1','1','Great Lakes Giant Schnauzer Rescue','7021 Surrey Dr','1','Phone: 513-750-5895','Email: glgsr801@yahoo.com'),
('2','1','Max''s Animal Mission','7084 Ketch Harbour St','2','Phone: 614-284-1465','Email: maxsanimalmission@gmail.com'),
('3','1','Jack of Hearts','14368 Tomscot Court','3','Phone: (740) 334-9921','Email: k9training77@gmail.com'),
('4','1','Stop the Suffering','7970 Canary Plaza','4','Phone: (614) 395-0058','Email: info@stopthesuffering.org'),
('5','1','Buckeye Pet Partners','8 Oak Valley Circle','5','Phone: 7403922287','Email: Adoptions@buckeyepetpartners.org'),
('6','1','Colony Cats & dogs','14 Village Green Point','6','Phone: 614-268-6096','Email: ColonyCatsdogs@gmail.com'),
('7','1','The Animal Shelter Society Inc.','95744 Holy Cross Street','7','Phone: 614-491-1222','Email: assirescue@rrohio.com'),
('8','1','Black and Orange','970 Surrey Junction','8','Phone: (614) 555-5555','Email: bevandjack1@gmail.com'),
('9','1','I Have A Dream Rescue Organization','5 Westport Drive','9','Phone: 614-448-1488 ','Email: adoption@ihadro.org'),
('10','1','Rescue Me','56674 Little Fleur Point','10','Phone: (419) 210-1967','Email: feralrescue@windstream.net')
SET IDENTITY_INSERT [shelter] OFF


--==================================================================================================dogbreeds databse
CREATE TABLE section(
Id					INT				PRIMARY KEY				IDENTITY,
section				VARCHAR(50)		NOT NULL
)
 

 CREATE TABLE country(
 Id					INT				PRIMARY KEY				IDENTITY,
country				VARCHAR(50)		NOT NULL,
 )


CREATE TABLE dogbreeds(
dogId				INT				PRIMARY KEY				IDENTITY,
name				VARCHAR(50)		NOT NULL,		
section_id			INT				FOREIGN KEY REFERENCES dbo.section(Id),
country_id			INT				FOREIGN KEY REFERENCES dbo.country(Id),
image				VARCHAR(150)
) 

--==========================================Insert Value into section
SET IDENTITY_INSERT [section] ON;
INSERT INTO section(Id,section) VALUES ('1','Sporting Group'),
                                       ('2','Working Group'),
									   ('3','Toy Group'),
									   ('4','Herding Group'),
									   ('5','Foundation Stock Service'),
									   ('6','Hound Group'),
									   ('7','Terrier Group'),
									   ('8','Non-Sporting Group'),
									   ('9','Miscellaneous Class')
SET IDENTITY_INSERT [section] OFF

--==========================================Insert Value into country
SET IDENTITY_INSERT [country] ON;
INSERT INTO country(Id,country) VALUES ('1','United States'),
									   ('2','England'),
									   ('3','South Africa'),
									   ('4','Chinese'),
									   ('5','Malta'),
									   ('6','Belgian'),
									   ('7', ' Russia'),
									   ('8','Switzerland')
SET IDENTITY_INSERT [country] OFF

--==========================================Insert Value into dogbreeds
SET IDENTITY_INSERT [dogbreeds] ON;
INSERT INTO dogbreeds(dogId,name,section_id,country_id,image) VALUES
('1','American Water Spaniel','1','1','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/04160609/American-Water-Spaniel.1.jpg'),
('2','English Springer Spaniel', '1','2','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/17145647/English-Springer-Spaniel-5.jpg'),
('3','Boerboel','2','3','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/13000039/Boerboel-On-White-03.jpg'),
('4','Chinese Crested','3','4','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/12234649/Chinese-Crested-On-White-01.jpg'),
('5','Maltese','3','5','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/12231006/Maltese-On-White-04.jpg'),
('6','Belgian Malinois','4','6','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/13000724/Belgian-Malinois-On-White-01.jpg'),
('7','Bull Terrier','7','2','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/12235344/Bull-Terrier-On-White-03.jpg'),
('8','Beagle','6','2','https://cdn1-www.dogtime.com/assets/uploads/2011/01/file_23012_beagle-460x290.jpg'),
('9','Borzoi','6','7','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/01132030/Borzoi-On-White-031.jpg'),
('10','Appenzeller Sennenhund','5','8','https://s3.amazonaws.com/cdn-origin-etr.akc.org/wp-content/uploads/2017/11/07182705/AdobeStock_81980916.jpg')
SET IDENTITY_INSERT [dogbreeds] OFF
