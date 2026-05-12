--
-- Table: tx_dentistdirectory_domain_model_dentist
--
CREATE TABLE tx_dentistdirectory_domain_model_dentist (
    uid            int(11) NOT NULL AUTO_INCREMENT,
    pid            int(11) DEFAULT 0 NOT NULL,
    tstamp         int(11) DEFAULT 0 NOT NULL,
    crdate         int(11) DEFAULT 0 NOT NULL,
    deleted        tinyint(4) DEFAULT 0 NOT NULL,
    hidden         tinyint(4) DEFAULT 0 NOT NULL,
    sorting        int(11) DEFAULT 0 NOT NULL,
    sys_language_uid int(11) DEFAULT 0 NOT NULL,
    l10n_parent    int(11) DEFAULT 0 NOT NULL,
    l10n_diffsource mediumblob,

    name           varchar(255) DEFAULT '' NOT NULL,
    slug           varchar(255) DEFAULT '' NOT NULL,
    specialization varchar(255) DEFAULT '' NOT NULL,
    address        varchar(500) DEFAULT '' NOT NULL,
    district       varchar(100) DEFAULT '' NOT NULL,
    city           varchar(100) DEFAULT 'Bucharest' NOT NULL,
    phone          varchar(50)  DEFAULT '' NOT NULL,
    email          varchar(255) DEFAULT '' NOT NULL,
    website        varchar(500) DEFAULT '' NOT NULL,
    description    text,
    working_hours  text,
    image          int(11) DEFAULT 0 NOT NULL,
    latitude       decimal(10,7) DEFAULT NULL,
    longitude      decimal(10,7) DEFAULT NULL,
    categories     int(11) DEFAULT 0 NOT NULL,

    -- listing tier
    is_featured    tinyint(1) DEFAULT 0 NOT NULL,
    is_claimed     tinyint(1) DEFAULT 0 NOT NULL,
    listing_tier   varchar(20) DEFAULT 'free' NOT NULL COMMENT 'free|basic|premium',

    -- moderation
    status         varchar(20) DEFAULT 'pending' NOT NULL COMMENT 'pending|approved|rejected',
    moderator_note text,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY slug   (slug(191))
);

--
-- Table: tx_dentistdirectory_domain_model_category
--
CREATE TABLE tx_dentistdirectory_domain_model_category (
    uid   int(11) NOT NULL AUTO_INCREMENT,
    pid   int(11) DEFAULT 0 NOT NULL,
    name  varchar(255) DEFAULT '' NOT NULL,
    slug  varchar(255) DEFAULT '' NOT NULL,
    icon  varchar(255) DEFAULT '' NOT NULL,
    deleted  tinyint(4) DEFAULT 0 NOT NULL,
    hidden   tinyint(4) DEFAULT 0 NOT NULL,
    PRIMARY KEY (uid),
    KEY parent (pid)
);

--
-- MM relation: dentist <-> category
--
CREATE TABLE tx_dentistdirectory_dentist_category_mm (
    uid_local   int(11) DEFAULT 0 NOT NULL,
    uid_foreign int(11) DEFAULT 0 NOT NULL,
    sorting     int(11) DEFAULT 0 NOT NULL,
    sorting_foreign int(11) DEFAULT 0 NOT NULL,
    KEY uid_local   (uid_local),
    KEY uid_foreign (uid_foreign)
);

--
-- Table: tx_dentistdirectory_domain_model_claim
--
CREATE TABLE tx_dentistdirectory_domain_model_claim (
    uid         int(11) NOT NULL AUTO_INCREMENT,
    pid         int(11) DEFAULT 0 NOT NULL,
    tstamp      int(11) DEFAULT 0 NOT NULL,
    crdate      int(11) DEFAULT 0 NOT NULL,
    deleted     tinyint(4) DEFAULT 0 NOT NULL,

    dentist     int(11) DEFAULT 0 NOT NULL,
    claimant_name  varchar(255) DEFAULT '' NOT NULL,
    claimant_email varchar(255) DEFAULT '' NOT NULL,
    claimant_phone varchar(50)  DEFAULT '' NOT NULL,
    proof_document int(11) DEFAULT 0 NOT NULL COMMENT 'sys_file reference uid',
    message        text,
    token          varchar(64) DEFAULT '' NOT NULL,
    status         varchar(20) DEFAULT 'pending' NOT NULL COMMENT 'pending|approved|rejected',
    reviewed_at    int(11) DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent  (pid),
    KEY dentist (dentist)
);

--
-- Table: tx_dentistdirectory_domain_model_subscription
--
CREATE TABLE tx_dentistdirectory_domain_model_subscription (
    uid         int(11) NOT NULL AUTO_INCREMENT,
    pid         int(11) DEFAULT 0 NOT NULL,
    tstamp      int(11) DEFAULT 0 NOT NULL,
    crdate      int(11) DEFAULT 0 NOT NULL,
    deleted     tinyint(4) DEFAULT 0 NOT NULL,

    dentist     int(11) DEFAULT 0 NOT NULL,
    plan        varchar(20) DEFAULT 'basic' NOT NULL COMMENT 'basic|premium',
    price_eur   decimal(8,2) DEFAULT 0.00 NOT NULL,
    starts_at   int(11) DEFAULT 0 NOT NULL,
    ends_at     int(11) DEFAULT 0 NOT NULL,
    payment_ref varchar(255) DEFAULT '' NOT NULL,
    status      varchar(20) DEFAULT 'active' NOT NULL COMMENT 'active|cancelled|expired',

    PRIMARY KEY (uid),
    KEY parent  (pid),
    KEY dentist (dentist)
);

--
-- Table: tx_dentistdirectory_domain_model_lead
--
CREATE TABLE tx_dentistdirectory_domain_model_lead (
    uid          int(11) NOT NULL AUTO_INCREMENT,
    pid          int(11) DEFAULT 0 NOT NULL,
    tstamp       int(11) DEFAULT 0 NOT NULL,
    crdate       int(11) DEFAULT 0 NOT NULL,
    deleted      tinyint(4) DEFAULT 0 NOT NULL,

    dentist      int(11) DEFAULT 0 NOT NULL,
    sender_name  varchar(255) DEFAULT '' NOT NULL,
    sender_email varchar(255) DEFAULT '' NOT NULL,
    sender_phone varchar(50)  DEFAULT '' NOT NULL,
    message      text,
    is_read      tinyint(1) DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent  (pid),
    KEY dentist (dentist)
);
