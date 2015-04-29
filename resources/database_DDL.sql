#------------------------------------------------------------------------------------
#----------------------------------------User----------------------------------------
#------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user(
	uID INT NOT NULL AUTO_INCREMENT,
	email VarChar(255) NOT NULL UNIQUE,
	pass Char(128) NOT NULL,
	picture VarChar(255),
	f_name VarChar(255) NOT NULL,
	m_name VarChar(255),
	l_name VarChar(255) NOT NULL, 
	tags_visible TINYINT(1) NOT NULL,
	profile_visible TINYINT(1) NOT NULL,
	block_invites TINYINT NOT NULL,
	block_messages TINYINT NOT NULL,
	PRIMARY KEY (uID)
);

CREATE TABLE IF NOT EXISTS contacts(
	uID INT NOT NULL,
	contact INT NOT NULL,
	PRIMARY KEY(uID, contact),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (contact) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS u_blocks (
	uID INT NOT NULL,
	blocked INT NOT NULL,
	PRIMARY KEY (uID, blocked),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (blocked) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE
);


#--------------------------------------------------------------------------------------------
#----------------------------------------Conversation----------------------------------------
#--------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS conversation(
	cID INT NOT NULL AUTO_INCREMENT UNIQUE,
	c_name VarChar(255),
	PRIMARY KEY(cID)
);

CREATE TABLE IF NOT EXISTS participates (
	uID INT NOT NULL,
	cID INT NOT NULL,
	joined DATETIME NOT NULL,
	PRIMARY KEY(uID,cID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cID) REFERENCES conversation(cID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS message (
	mID INT NOT NULL AUTO_INCREMENT,
	uID INT NOT NULL,
	cID INT NOT NULL,
	date_time DATETIME NOT NULL,
	content VarChar(255),
	PRIMARY KEY(mID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cID) REFERENCES conversation(cID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS unreadMessages(
	uID INT NOT NULL,
	cID INT NOT NULL,
	count INT NOT NULL,
	PRIMARY KEY (uID,cID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cID) REFERENCES participates(cID) ON DELETE CASCADE ON UPDATE CASCADE
);


#-------------------------------------------------------------------------------------
#----------------------------------------Group----------------------------------------
#-------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS groups(
	gID INT NOT NULL AUTO_INCREMENT UNIQUE,
	g_name VarChar(255) NOT NULL,
	icon VarChar(255),
	visible TINYINT NOT NULL, #three different options: 2 - everyone can see and participate, 1 - everyone can see but not participate, 0 - only members can see and participate
	burn_date DATETIME,
	PRIMARY KEY (gID)
);

CREATE TABLE IF NOT EXISTS members(
	uID INT NOT NULL,
	gID INT NOT NULL,
	moderator TINYINT(1),
	joined DATETIME NOT NULL,
	PRIMARY KEY(uID, gID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (gID) REFERENCES groups(gID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS post (
	pID INT NOT NULL AUTO_INCREMENT,
	uID INT NOT NULL,
	gID INT NOT NULL,
	date_time DATETIME NOT NULL,
	content TEXT NOT NULL,
	edited TINYINT(1) NOT NULL,
	PRIMARY KEY(pID),
	FOREIGN KEY (uID) REFERENCES members(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (gID) REFERENCES groups(gID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS unreadPosts(
	uID INT NOT NULL,
	gID INT NOT NULL,
	count INT NOT NULL,
	PRIMARY KEY (uID,gID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (gID) REFERENCES members(gID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS reply(
	pID INT NOT NULL,
	parent INT NOT NULL,
	PRIMARY KEY(pID,parent),
	FOREIGN KEY (pID) REFERENCES post(pID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (parent) REFERENCES post(pID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS g_blocks (
	gID INT NOT NULL,
	uID INT NOT NULL,
	PRIMARY KEY (uID,gID),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (gID) REFERENCES groups(gID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS g_owns (
	gID INT NOT NULL,
	cID INT NOT NULL,
	o_participation TINYINT(1) NOT NULL, #whether or not the public can participate in the group
	PRIMARY KEY (gID,cID),
	FOREIGN KEY (gID) REFERENCES groups(gID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cID) REFERENCES conversation(cID) ON DELETE CASCADE ON UPDATE CASCADE
);


#-----------------------------------------------------------------------------------
#----------------------------------------Tag----------------------------------------
#-----------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tag (
	tag_name VarChar(255) NOT NULL,
	PRIMARY KEY(tag_name)
);

CREATE TABLE IF NOT EXISTS u_tagged (
	uID INT NOT NULL,
	tag_name VarChar(255) NOT NULL,
	PRIMARY KEY(uID, tag_name),
	FOREIGN KEY (uID) REFERENCES user(uID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (tag_name) REFERENCES tag(tag_name) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS g_tagged (
	gID INT NOT NULL,
	tag_name VarChar(255) NOT NULL,
	PRIMARY KEY(gID, tag_name),
	FOREIGN KEY (gID) REFERENCES groups(gID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (tag_name) REFERENCES tag(tag_name) ON DELETE CASCADE ON UPDATE CASCADE
);