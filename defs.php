<?php
define('DB_HOST', 'localhost');


//OTHER
define('SECRET_KEY', 'SHOPM');

//define items cats
define('ROOT_FOLDER_SOSACHAT', 'sosachat');
define('ROOT_FOLDER_IMAGES', 'img');
define('FOLDER_NAME_PRODUCTS', 'products');
define('FOLDER_NAME_PROFILE_PICTURES', 'pp');
define('FOLDER_NAME_CATS', 'cats');
define('SMALL', 'small');
define('KEY_MTIME_PP', 'mtime_pp');

define('PROD_STAT_UNPUBLISHED', '0');
define('PROD_STAT_WAITING', '1');
define('PROD_STAT_PUBLISHED', '2');
define('PROD_STAT_DENIED', '3');

define('ERR_EMPTY', 'resEmpty');


define('POSTFIX_PRODUCT_IMAGE_MAIN', '_main');
define('POSTFIX_PRODUCT_IMAGE_PIC1', '_pic1');
define('POSTFIX_PRODUCT_IMAGE_PIC2', '_pic2');
define('POSTFIX_PRODUCT_IMAGE_PIC3', '_pic3');


define('CAT_CARS', 'cat_cars');
define('CAT_ELEC', 'cat_elec');
define('CAT_OTHER', 'cat_other');

define('KEY_MESSAGE_TYPE_INBOX', 0);
define('KEY_MESSAGE_TYPE_OUTBOX', 1);

define('KEY_MESSAGE_TYPE',"msgType");
define('KEY_MESSAGE_DATE',"msgDate");
define('KEY_MESSAGE_FROM_ID',"msgFromID");
define('KEY_MESSAGE_TO_ID',"msgToID");
define('KEY_MESSAGE_CONTENT',"msgCont");
define('KEY_MESSAGE_TITLE',"msgTitle");

define('NO_PIC', 'no_pic.jpg');

//table names
define('TABLE_NAME_ITEMS_CATEGORY','it_cats');
define('TABLE_NAME_ITEMS_TYPES','items_types');

define('IMG_FOLDER_ROOT', 'img');
define('KEY_ITEM_NO_PIC', 'no_pic');
define('IMG_FOLDER_PRODUCTS', 'products');

define('IMG_FOLDER_PP', 'pp');

define('JSON_RESPONSE_FALSE', "{result:'false'}");
define('KEY_PRODUCT_OWNER_ID', 'pdOwnerId');

define('DIR_PRODUCTS_PIX', 'img/products/');

define('IMAGE_UPLOAD_FORM_NAME', 'uploaded_file');
define('KEY_REQ_REL_ROOT_DIR', 'relRootDir');
define('KEY_REQ_SERVER_FILE_NAME','sfn');
define('KEY_RES_PATH_PROFILE_PICTURE', 'path_pp');

////////////////////////
//Android : NETWORK_RESULT_CODES.java
// RESULT CODES
define('KEY_RESULT_CODE', 'code');
define('KEY_RESULT_DATA', 'data');
//------------------------------
define('RESULT_CODE_EXCEPTION', 0);
define('RESULT_CODE_SUCCESS', 1);
define('RESULT_CODE_FAILURE', 2);
define('RESULT_CODE_NETWORK_ERROR', 3);
define('RESULT_CODE_EMPTY_LIST', 4);
define('RESULT_CODE_USER_DONT_EXIST', 5);
define('RESULT_CODE_USER_CONNECTION_SUCCESS', 6);
define('RESULT_CODE_USER_CONNECTION_FAILURE_PASSWORD_ERROR', 7);
define('RESULT_CODE_SIGNUP_FAILURE_USER_EXISTS', 8);
define('RESULT_CODE_SIGNUP_SUCCESS', 9);
define('RESULT_CODE_SIGNUP_FAILURE', 10);



?>