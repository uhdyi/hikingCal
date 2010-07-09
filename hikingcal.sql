#
# Table structure for table 'hikingcal'
#

CREATE TABLE hikingcal (
id int(10) unsigned NOT NULL auto_increment,
hikingDate date,
trail varchar(100),
location varchar(100),
buddy varchar(50),
PRIMARY KEY  (id)
)
