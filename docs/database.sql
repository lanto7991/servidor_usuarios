create database TenTen;

use TenTen;

create table usuarios(
   id_usuarios int auto_increment,
   username varchar(30),
   pass_user varchar(30),
   PRIMARY KEY(id_usuarios));
   
create table clientes(
   id_cliente int auto_increment,
   nombre_cliente varchar(30),
   apellido_cliente varchar(30),
   dni_cliente int(30),
   anio_cliente YEAR(5),
   PRIMARY KEY (id_cliente));