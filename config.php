<?php
//mengabaikan pesan Notice
error_reporting(E_ALL ^ (E_NOTICE));

$kon = pg_connect("host=localhost dbname=lokasi user=postgres password=postgres");;
if(!$kon) {
	echo "Gagal melakukan koneksi ";
}

/*
CREATE TABLE lokasimu (
    id bigserial,
    foto text,
    lokasi character varying(255),
    lat double precision,
    long double precision,
    ket text,
	PRIMARY KEY (id)
);
*/

?>