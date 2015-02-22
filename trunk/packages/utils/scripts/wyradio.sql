CREATE TABLE shows (
    "name" STRING NOT NULL,
    "acronym" VARCHAR(10) NOT NULL,
    "streamsourceid" VARCHAR NOT NULL,
    "duration" INTEGER NOT NULL,
    "outsinglefile" BOOLEAN NOT NULL DEFAULT (1),
    "minute" INTEGER,
    "hour" INTEGER,
    "monthday" INTEGER,
    "weekday" INTEGER,
    "month" INTEGER
);


CREATE TABLE streamsources (
    "name" STRING NOT NULL,
    "acronym" VARCHAR(10) NOT NULL,
    "url" TEXT NOT NULL,
    "outfolder" TEXT NOT NULL
);


