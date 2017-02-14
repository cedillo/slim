
--Delete from sia_cuentasingresos
Select * from sia_cuentasingresos order by clave

--sp_rename 'sia_cuentasingresos.concepto', 'origen', 'COLUMN'; 

--ALTER TABLE sia_cuentasingresos ALTER COLUMN usrModificacion varchar(20) NULL;

--//// REPORTE #4

SELECT origen, tipo, clave, nombre, SUM(CAST(oriOld AS NUMERIC(15,2))) oriOld, SUM(CAST(recOld AS NUMERIC(15,2))) recOld, SUM(CAST(oriNew AS NUMERIC(15,2))) oriNew, SUM(CAST(recNew AS NUMERIC(15,2))) recNew
FROM(
  SELECT origen, tipo, clave, nombre, original oriOld, recaudado recOld, '0' oriNew, '0' recNew FROM sia_cuentasingresos
UNION ALL
  SELECT origen, tipo, clave, nombre, '0' oriOld, '0' recOld, '0' oriNew, '0' recNew FROM sia_cuentasingresos
) d 
group by origen, tipo, clave, nombre
order by clave


///////////////////////////////////////////////////// LISTA DE REPORTES
Select * from sia_reportes 
Select * from sia_reportesParametros

Update sia_reportes set archivo='reporte4.php', nombre='INGRESOS DEL SECTOR GOBIERNO' where idReporte=4

Insert into sia_reportesParametros 
(idModulo, idReporte, tipo, etiqueta, globo, ancho, dominio, consulta, predeterminado, estatus )  values('CIUDADANOS', 4, 'CMB', 'Año Inicial', '','30', 'SQL', 'Select idCuenta id, anio texto FROM sia_cuentas ORDER BY anio', '', 'estatus');

Insert into sia_reportesParametros 
(idModulo, idReporte, tipo, etiqueta, globo, ancho, dominio, consulta, predeterminado, estatus )  values('CIUDADANOS', 4, 'CMB', 'Año Final', '','30', 'SQL', 'Select idCuenta id, anio texto FROM sia_cuentas ORDER BY anio', '', 'estatus');



SELECT d.capitulo, c.nombre, 
			CONVERT(DECIMAL(20,2), sum(d.eje14)/1000 ) eje14,
      CONVERT(DECIMAL(20,2), sum(d.ori15)/1000) ori15, 
			CONVERT(DECIMAL(20,2), sum(d.mod15)/1000) mod15,
      CONVERT(DECIMAL(20,2), sum(d.eje15)/1000) eje15, 
			CONVERT(DECIMAL(20,2), sum(d.eje15 - d.eje14)/1000) varEje, 
      CONVERT(DECIMAL(20,2), sum(d.eje15 - d.ori15)/1000) varEjeOri, 
      CONVERT(DECIMAL(20,2), sum(d.eje15 - d.mod15)/1000) varEjeMod 
			FROM( 
				SELECT capitulo, sum(CONVERT(DECIMAL(20,2), ejercido)) eje14 , '0' ori15, '0' mod15, '0' eje15  
        FROM sia_cuentasdetalles WHERE idCuenta='CTA-2014' GROUP BY capitulo 
			UNION  ALL 
      
				SELECT capitulo, '0' eje14 , sum(convert(DECIMAL(20,2), original)) ori15, 
        sum(convert(DECIMAL(20,2), modificado)) mod15, 
        sum(convert(DECIMAL(20,2), ejercido)) eje15 
        FROM sia_cuentasdetalles WHERE idCuenta='CTA-2015' GROUP BY capitulo 
        
			) d left join sia_capitulos c on d.capitulo=c.idCapitulo  
     GROUP BY d.capitulo, c.nombre 
     ORDER BY d.capitulo, c.nombre
     

DECIMAL(20,2)



SELECT  sum(convert(DECIMAL(20,2), original)) ori15, 
        sum(convert(DECIMAL(20,2), modificado)) mod15, 
        sum(convert(DECIMAL(20,2), ejercido)) eje15 
        FROM sia_cuentasdetalles WHERE idCuenta='CTA-2015'
        
        Select original, modificado, ejercido FROM sia_cuentasdetalles WHERE  ejercido=''
        
        Update sia_cuentasdetalles set original='0' WHERE original is null or original=''

SELECT  capitulo, partida, ejercido  , '0' ori15, '0' mod15, '0' eje15  FROM sia_cuentasdetalles 
        WHERE idCuenta='CTA-2014'  order by 3


SELECT d.capitulo, c.nombre sCapitulo, d.partida, p.nombre sPartida, 
			CONVERT(DECIMAL(20,2), sum(d.eje14)/1000) eje14,CONVERT(DECIMAL(20,2), sum(d.ori15)/1000) ori15, 
			CONVERT(DECIMAL(20,2), sum(d.mod15)/1000) mod15,CONVERT(DECIMAL(20,2), sum(d.eje15)/1000) eje15, 
			CONVERT(DECIMAL(20,2), sum(d.eje15 - d.eje14)/1000) varEje, CONVERT(DECIMAL(20,2), sum(d.eje15 - d.ori15)/1000) varEjeOri, CONVERT(DECIMAL(20,2), sum(d.eje15 - d.mod15)/1000) varEjeMod 
			FROM( 
				SELECT  capitulo, partida, sum(CONVERT(DECIMAL(20,2), ejercido)) eje14 , '0' ori15, '0' mod15, '0' eje15  FROM sia_cuentasdetalles 
        WHERE idCuenta='CTA-2014' GROUP BY capitulo, partida 
			UNION  ALL 
				SELECT capitulo, partida, '0' eje14 , sum(CONVERT(DECIMAL(20,2), original)) ori15, 
        sum(CONVERT(DECIMAL(20,2), modificado)) mod15, sum(CONVERT(DECIMAL(20,2), ejercido)) eje15 
        FROM sia_cuentasdetalles WHERE idCuenta='CTA-2015' GROUP BY capitulo, partida 
			) d 
			left join sia_capitulos c on d.capitulo=c.idCapitulo	 
			left join sia_partidas p on d.capitulo=p.idCapitulo and d.partida=p.idPartida 
			GROUP BY d.capitulo, c.nombre, d.partida, p.nombre ORDER BY d.capitulo, c.nombre, d.partida, p.nombre ;
      
      
      SElect * from sia_unidades
      Select * from sia_capitulos
      
      Select count(*) from sia_cuentasdetalles where idCuenta='CTA-2015'
      
      
      Select * from sia_auditorias
      
      
      Select * from sia_areas
      
      ///////////CREAR TABLAS PARA ARIA
      drop table sia_auditores;
      create table sia_auditores(
        
        idAuditor varchar(20) not null,
        
        idArea varchar(20) null,
        idPuesto varchar(20)null,
        idNivel varchar(20) null,
        idNombramiento varchar(20) null,
        idPlaza varchar(20) null,
       
        
        nombre varchar(50) not null,
        paterno varchar(50) not null,
        materno varchar(50) not null,
        
        usrAlta varchar(20) not null,
        fAlta datetime not null,
        usrModificacion varchar(20) null,
        fModificacion datetime  null,        
        estatus varchar(20)  null,        
      );
      ALTER TABLE sia_auditores ADD CONSTRAINT pk_auditores PRIMARY KEY (idAuditor);
      
      
      
      Drop table sia_auditoriasauditores;
      create table sia_auditoriasauditores(
        idCuenta varchar(20) not null,
        idPrograma varchar(20) not null,
        idAuditoria varchar(20) not null,        
        idAuditor varchar(20) not null,        
        usrAlta varchar(20) not null,
        fAlta datetime not null,
        usrModificacion varchar(20) null,
        fModificacion datetime null,        
        estatus varchar(20) null        
      );
      ALTER TABLE sia_auditoriasauditores ADD CONSTRAINT pk_audi1audi2 PRIMARY KEY (idCuenta, idPrograma, idAuditoria, idAuditor);
      
      
      Select * from sia_areas;
      Select * from sia_empleados
      
Select * from sia_auditorias

Update sia_auditorias set idArea='DGACFA'

Select * from sia_auditores where idArea in ('DGACFA', 'DGACFB', 'DGACFC', 'DGAEA', 'DGAEB');

Select * from sia_auditorias where idArea in ('DGACFA');
Select * from sia_auditores where idArea in ('DGACFA');



Insert into sia_auditoriasauditores (idCuenta, idPrograma, idAuditoria, idAuditor, usrAlta, fAlta, estatus)
Select distinct a1.idCuenta, a1.idPrograma, a1.idAuditoria, a2.idAuditor, 'COTA', getdate(), 'ACTIVO'
from sia_auditorias a1 inner join sia_auditores a2 on a1.idArea=a2.idArea 
where a1.idArea in ('DGACFA')  and a1.idCuenta='CTA-2014' 


ALTER TABLE sia_plazas ALTER COLUMN usrAlta varchar(20)
ALTER TABLE sia_plazas ALTER COLUMN usrModificacion varchar(20) NULL
ALTER TABLE sia_plazas ALTER COLUMN fModificacion datetime null;

Select * from sia_auditoriasauditores

Select * from sia_plazas
Select * from sia_puestos
Select * from sia_nombramientos
Select * from sia_niveles

alter table sia_plazas usrAlta varchar(20)

ALTER TABLE sia_plazas DROP CONSTRAINT primary_key19;

ALTER TABLE sia_plazas ADD CONSTRAINT pk_plazas PRIMARY KEY (idArea, idPuesto, idNivel, idNombramiento, idPlaza);

ALTER TABLE sia_auditores  ADD FOREIGN KEY (idArea, idPuesto, idNivel, idNombramiento, idPlaza ) REFERENCES sia_plazas(idArea, idPuesto, idNivel, idNombramiento, idPlaza);

Select * from sia_plazas

Select p.idNivel, a.nombre, a.paterno, p.nombre  
from sia_auditores a inner join sia_plazas p on p.idPlaza=a.idPlaza 
where p.idArea in ('DGACFA', 'DGACFB', 'DGACFC', 'DGAEA', 'DGAEB')
order by p.idNivel desc


Select * from sia_tiposauditoria

create table sia_criterios(
  idTipoAuditoria varchar(20) not null,
  idCriterio varchar(20) not null,
  nombre varchar(100) not null,
  usrAlta varchar(20) not null,
  fAlta datetime not null,
  usrModificacion varchar(20) null,
  fModificacion datetime null,        
  estatus varchar(20) null     
);
ALTER TABLE sia_criterios ADD CONSTRAINT pk_criterios PRIMARY KEY (idTipoAuditoria, idCriterio);
ALTER TABLE sia_tiposauditoria ADD CONSTRAINT pk_tipAudi PRIMARY KEY (idTipoAuditoria);
ALTER TABLE sia_criterios  ADD FOREIGN KEY (idTipoAuditoria ) REFERENCES sia_tiposauditoria(idTipoAuditoria);

Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('CUMPLIMIENTO', 'IMP-RELATIVA', 'IMPORTANCIA RELATIVA', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('FINANCIERA', 'IMP-RELATIVA', 'IMPORTANCIA RELATIVA', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('OBRA', 'IMP-RELATIVA', 'IMPORTANCIA RELATIVA', 'COTA', GETDATE(), 'ACTIVO');

Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('CUMPLIMIENTO', 'PRES-COBER', 'PRESENCIA Y COBERTURA', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('FINANCIERA', 'PRES-COBER', 'PRESENCIA Y COBERTURA', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('OBRA', 'PRES-COBER', 'PRESENCIA Y COBERTURA', 'COTA', GETDATE(), 'ACTIVO');

Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('CUMPLIMIENTO', 'COBER-GASTO', 'COBERTURA DE LOS ÁMBITOS DEL CAPÍTULO DEL GASTO', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('FINANCIERA', 'COBER-GASTO', 'COBERTURA DE LOS ÁMBITOS DEL CAPÍTULO DEL GASTO', 'COTA', GETDATE(), 'ACTIVO');
Insert into sia_criterios (idTipoAuditoria, idCriterio, nombre, usrAlta, fAlta, estatus) values('OBRA', 'COBER-GASTO', 'COBERTURA DE LOS ÁMBITOS DEL CAPÍTULO DEL GASTO', 'COTA', GETDATE(), 'ACTIVO');


Select * from sia_auditorias
Select * from sia_criterios

      create table sia_auditoriascriterios(
        idCuenta varchar(20) not null,
        idPrograma varchar(20) not null,
        idAuditoria varchar(20) not null,        
        idCriterio varchar(20) not null,
        justificacion varchar(300) not null,
        elementos varchar(300) not null,
        usrAlta varchar(20) not null,
        fAlta datetime not null,
        usrModificacion varchar(20) null,
        fModificacion datetime null,        
        estatus varchar(20) not null        
      );
      ALTER TABLE sia_auditoriascriterios ADD CONSTRAINT pk_auditCrit PRIMARY KEY (idCuenta, idPrograma, idAuditoria, idCriterio);
      ALTER TABLE sia_auditoriascriterios  ADD FOREIGN KEY (idCriterio ) REFERENCES sia_criterios(idCriterio);      

Select * from sia_unidades
Select * from sia_objetos


--////////////////////  en proceso //////////////////////////////
    --drop table sia_objetos;
    create table sia_objetos(
        idCuenta varchar(20) not null,
        idSector varchar(20) not null,
        idSubsector varchar(20) not null,
        idUnidad varchar(20) not null,
        idObjeto int  identity(1,1) not null,        
        tipo varchar(20) not null,
        justificacion varchar(300) not null,
        elementos varchar(300) not null,
        usrAlta varchar(20) not null,
        fAlta datetime not null,
        usrModificacion varchar(20) null,
        fModificacion datetime null,        
        estatus varchar(20) not null        
      );
      ALTER TABLE sia_auditoriascriterios ADD CONSTRAINT pk_auditCrit PRIMARY KEY (idCuenta, idPrograma, idAuditoria, idCriterio);
      ALTER TABLE sia_auditoriascriterios  ADD FOREIGN KEY (idCriterio ) REFERENCES sia_criterios(idCriterio);   
--//////////////////////////////////////////////////      


Select * from sia_usuarios
Select * from sia_auditores



Select p.idNivel, a.idAuditor, a.nombre, a.paterno, a.materno,  p.nombre  
from sia_auditores a inner join sia_plazas p on p.idPlaza=a.idPlaza 
where p.idArea in ('DGACFC')
order by p.idNivel desc

//
SELECT * Into sia_empleados From sia_auditores
Select idEmpleado id, nombre + ' ' + paterno + ' ' + materno texto from sia_empleados Where idArea='DGACFA'
sp_rename 'sia_empleados.idAuditor', 'idEmpleado', 'COLUMN'; 
ALTER TABLE sia_empleados ADD CONSTRAINT pk_empleados PRIMARY KEY (idEmpleado);


//CARGAR USUARIOS CON AREAS/////////////////////////////////
Select * from sia_usuarios
3.1	1795	JAIME ENRIQUE	CRUZ	MARTINEZ	AUDITOR FISCALIZADOR C DE LA DIRECCION GENERAL DE AUDITORIA DE CUMPLIMIENTO FINANCIERO C

sp_rename 'sia_usuarios.rpe', 'idEmpleado', 'COLUMN';


Update sia_usuarios 
set idEmpleado='1795', nombre='JAIME ENRIQUE', paterno='CRUZ', materno='MARTINEZ'  
where usuario='admin'; 

SELECT u.idUsuario, u.idEmpleado, CONCAT(u.nombre, ' ', u.paterno, ' ', u.materno) nombre, e.idArea, p.nombre plaza 
FROM sia_usuarios u 
  inner join sia_empleados e on u.idEmpleado=e.idEmpleado 
  inner join sia_plazas p on e.idArea = p.idArea and e.idNivel = p.idNivel and e.idNombramiento = p.idNombramiento and e.idPuesto = p.idPuesto and e.idPlaza = p.idPlaza
WHERE usuario='admin' and pwd='123'


//////////////////////////////////////////////////////

Select * from sia_cuentasdetalles where idCuenta='CTA-2015'
Select count(*) from sia_cuentasdetalles where idCuenta='CTA-2015'

Select * from sia_areasunidades where idArea not in ('DGACFA', 'DGACFB', 'DGACFC', 'DGAEA', 'DGAEB')
--DELETE from sia_areasunidades WHERE  idArea='DGACFC'


SELECT u.idUnidad id, u.nombre texto FROM sia_unidades u INNER JOIN sia_areasunidades au on u.idSector = au.idSector and u.idSubsector = au.idSubsector and u.idUnidad = au.idUnidad 
Where u.idCuenta='CTA-2014' and u.idSector='01' and u.idSubsector='C0' ORDER BY u.nombre

Select idEmpleado id, nombre + ' ' + paterno + ' ' + materno texto from sia_empleados Where idArea='DGACFA'

ALTER TABLE sia_cuentasdetalles ALTER COLUMN usrModificacion varchar(20) NULL;

Select idCuenta, count(*) from sia_cuentasingresos group by idCuenta




Select * from sia_auditoriascriterios
Select distinct idCriterio from sia_criterios

Insert into sia_auditoriascriterios (idCuenta, idPrograma, idAuditoria, idCriterio, justificacion, elementos,  usrAlta, fAlta, estatus)
Select idCuenta, idPrograma, idAuditoria, 'COBER-GASTO', 'Just1', 'Elem1', 'JACZ', getdate(), 'ACTIVO'  from sia_auditorias Where idCuenta='CTA-2014'

Insert into sia_auditoriascriterios (idCuenta, idPrograma, idAuditoria, idCriterio, justificacion, elementos,  usrAlta, fAlta, estatus)
Select idCuenta, idPrograma, idAuditoria, 'IMP-RELATIVA', 'Just1', 'Elem1', 'JACZ', getdate(), 'ACTIVO'  from sia_auditorias Where idCuenta='CTA-2014'


Insert into sia_auditoriascriterios (idCuenta, idPrograma, idAuditoria, idCriterio, justificacion, elementos,  usrAlta, fAlta, estatus)
Select idCuenta, idPrograma, idAuditoria, 'PRES-COBER', 'Just1', 'Elem1', 'JACZ', getdate(), 'ACTIVO'  from sia_auditorias Where idCuenta='CTA-2014'




Select  c.idcriterio, c.nombre
from sia_auditoriascriterios ac inner join sia_criterios c on ac.idCriterio=c.idCriterio
Where ac.idAuditoria='ASCM-20160413203455'

Select  c.idcriterio id, c.nombre from sia_auditoriascriterios ac inner join sia_criterios c on ac.idCriterio=c.idCriterio
		WHERE ac.idAuditoria='ASCM-20160413203455'




Select * from sia_auditoriasauditores


Alter table sia_auditoriasauditores add lider varchar(20) null 


    Drop table sia_fases;
    create table sia_fases(
        idCuenta varchar(20) not null,
        idFase varchar(20) not null,
        nombre varchar(100) not null,
        orden int null,
        usrAlta varchar(20) not null,
        fAlta datetime not null,
        usrModificacion varchar(20) null,
        fModificacion datetime null,        
        estatus varchar(20) not null default 'ACTIVO'
      );
      ALTER TABLE sia_fases ADD CONSTRAINT pk_fase PRIMARY KEY (idFase);
      Insert into sia_fases (idCuenta, idFase, nombre, orden, usrAlta, fAlta, estatus) values('CTA-2014', 'PLANEACION', 'PLANEACION', 1, 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fases (idCuenta, idFase, nombre, orden, usrAlta, fAlta, estatus) values('CTA-2014', 'EJECUCION', 'EJECUCIÓN',2, 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fases (idCuenta, idFase, nombre, orden, usrAlta, fAlta, estatus) values('CTA-2014', 'INFORMES', 'INFORMES', 3, 'COTA', GETDATE(), 'ACTIVO');
      
    drop table sia_fasesactividades ;
    create table sia_fasesactividades(
        idCuenta varchar(20) not null,
        idFase varchar(20) not null,
        idActividad int  identity(1,1) not null,
        nombre varchar(100) not null,
        usrAlta varchar(20) not null,
        fAlta datetime not null default getdate(),
        usrModificacion varchar(20) null,
        fModificacion datetime null,        
        estatus varchar(20) not null default 'ACTIVO'
      );
      ALTER TABLE sia_fasesactividades ADD CONSTRAINT pk_faseAct PRIMARY KEY (idFase, idActividad);            
      ALTER TABLE sia_fasesactividades ADD FOREIGN KEY (idFase ) REFERENCES sia_fases(idFase); 
      
      
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'PLANEACION', 'Actividad #1', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'PLANEACION', 'Actividad #2', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'PLANEACION', 'Actividad #3', 'COTA', GETDATE(), 'ACTIVO');
      
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'EJECUCION', 'Actividad #4', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'EJECUCION', 'Actividad #5', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'EJECUCION', 'Actividad #6', 'COTA', GETDATE(), 'ACTIVO');
      
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'INFORMES', 'Actividad #7', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'INFORMES', 'Actividad #8', 'COTA', GETDATE(), 'ACTIVO');
      Insert into sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) values('CTA-2014', 'INFORMES', 'Actividad #9', 'COTA', GETDATE(), 'ACTIVO');
      
      
      
 --Para cargar el combo de fases
 ------------------------------------------------
Select idFase id, nombre texto From sia_fases Where idCuenta='CTA-2014' order by  orden    

--Para cargar el combo de las actividades
------------------------------------------------
Select idActividad id , nombre actividad texto From sia_fasesactividades Where  idCuenta='CTA-2014' AND  idFase='PLANEACION'
Order by idActividad

      
      
      
      
-- 23 de mayo 2016      


Select * from  sia_modulos
ALTER TABLE sia_modulos ALTER COLUMN icono varchar(50) NULL;
insert into sia_modulos (idModulo, nombre, panel, liga, orden, estatus) values('NORMATIVIDAD', 'Normatividad Vigente', 'DOCUMENTACION', './NORMATIVIDAD', '1', 'ACTIVO');


ALTER TABLE sia_modulostipos ALTER COLUMN XX INT  NULL;
insert into sia_modulostipos (idTipo, idModulo, estatus) values('ELECTORAL', 'NORMATIVIDAD', 'ACTIVO');
Select * from sia_modulostipos

insert into sia_rolesmodulos (idRol, idModulo, estatus) values('ADMINISTRADOR', 'NORMATIVIDAD', 'ACTIVO');
SELECT * from sia_roles

create table sia_roles (
  idRol varchar(20) not null,
  nombre varchar(100) not null,
  usrAlta varchar(20) not null,
  fAlta datetime not null default getdate(),
  usrModificacion varchar(20) null,
  fModificacion datetime null,        
  estatus varchar(20) not null default 'ACTIVO'
);

ALTER TABLE sia_roles ADD CONSTRAINT pk_roles PRIMARY KEY (idRol);
insert into sia_roles (idRol, nombre, usrAlta, fAlta, estatus) values('ADMINISTRADOR', 'ADMINISTRADOR DEL SISTEMA', 'COTA', GETDATE(), 'ACTIVO');
insert into sia_roles (idRol, nombre, usrAlta, fAlta, estatus) values('AUDITOR', 'AUDITOR', 'COTA', GETDATE(), 'ACTIVO');

Select distinct idRol from sia_rolesmodulos
Select * from sia_rolesmodulos

ALTER TABLE sia_rolesmodulos ALTER COLUMN usrAlta varchar(20) not NULL;

insert into sia_rolesmodulos (idRol, idModulo, usrAlta, fAlta, estatus) values('ADMINISTRADOR', 'NORMATIVIDAD', 'COTA', GETDATE(), 'ACTIVO');
insert into sia_rolesmodulos (idRol, idModulo, usrAlta, fAlta, estatus) values('AUDITOR', 'NORMATIVIDAD', 'COTA', GETDATE(), 'ACTIVO');

Select * from sia_modulos

Update sia_modulos set panel='NORMATIVIDAD', liga='./normatividad.php' WHERE idModulo='NORMATIVIDAD'

--Insertar una opcion al menu
insert into sia_modulos (idModulo, nombre, panel, liga, orden, estatus) values('NORMATIVIDAD', 'Normatividad Vigente', 'CONFIGURACION', './normatividades', '1', 'ACTIVO');
insert into sia_modulostipos (idTipo, idModulo, estatus) values('ELECTORAL', 'NORMATIVIDAD', 'ACTIVO');
insert into sia_rolesmodulos (idRol, idModulo, usrAlta, fAlta, estatus) values('ADMINISTRADOR', 'NORMATIVIDAD', 'COTA', GETDATE(), 'ACTIVO');
insert into sia_rolesmodulos (idRol, idModulo, usrAlta, fAlta, estatus) values('AUDITOR', 'NORMATIVIDAD', 'COTA', GETDATE(), 'ACTIVO');


Delete from sia_rolesmodulos WHere idModulo='CAT-FOLIOS';
Delete from sia_modulostipos WHere idModulo='CAT-FOLIOS';
Delete from sia_modulos WHere idModulo='CAT-FOLIOS';



drop table sia_normatividades;
create table sia_normatividades (
  idCuenta varchar(20) not null,
  idNormatividad integer identity(1,1) not null,
  nombre varchar(100) not null,
  tipo varchar(20) not null,
  acceso varchar(20) not null default 'PUBLICO',
  fInicio date not null,
  fFin date not null,
  usrAlta varchar(20) not null,
  fAlta datetime not null default getdate(),
  usrModificacion varchar(20) null,
  fModificacion datetime null,        
  estatus varchar(20) not null default 'ACTIVO'
);
ALTER TABLE sia_normatividades ADD CONSTRAINT pk_normat PRIMARY KEY (idNormatividad);

SELECT idNormatividad id, nombre, tipo, acceso, concat(fInicio, ' al ', fFin) vigencia, estatus 
FROM sia_normatividades





INSERT INTO sia_normatividades (idCuenta, nombre, tipo, acceso, fInicio, fFin, usrAlta, fAlta, estatus) 
VALUES('CTA-2014', 'NOMBRE', 'LEY', 'PUBLICO', '2016-05-23', '2016-12-31', 'COTA', getdate(), 'ACTIVO');



ALTER TABLE sia_rolesmodulos ALTER COLUMN usrAlta varchar(20) not NULL;

////RANGOS FOLIOS

--Insertar una opcion al menu
insert into sia_modulos (idModulo, nombre, panel, liga, orden, estatus) values('RANGOS', 'Rangos / Folios', 'CONFIGURACION', './rangos', '1', 'ACTIVO');
insert into sia_modulostipos (idTipo, idModulo, estatus) values('ELECTORAL', 'RANGOS', 'ACTIVO');
insert into sia_rolesmodulos (idRol, idModulo, usrAlta, fAlta, estatus) values('ADMINISTRADOR', 'RANGOS', 'COTA', GETDATE(), 'ACTIVO');

sp_rename 'sia_rangos.disponibles', 'disponible', 'COLUMN'; 


INSERT INTO sia_rangos (nombre, anio, siglas, inicio, fin, disponible, minimo, usrAlta, fAlta, estatus) 
VALUES('EJEMPLO RANGO #1', '2014', 'ascm/', 1, 150, 140, 20, 'JACZ', getdate(), 'ACTIVO');

ALTER TABLE sia_rangos ALTER COLUMN idRango integer identity(1,1) not NULL;



ALTER TABLE sia_rangos ALTER COLUMN usrModificacion varchar(20) NULL;
ALTER TABLE sia_rangos ALTER COLUMN fModificacion datetime NULL;

SELECT idRango id, nombre, anio, siglas, concat(inicio, ' al ', fin) rango, disponible, minimo, estatus   FROM sia_rangos  ORDER BY nombre 

ALTER TABLE sia_rangos ADD actual INT

Update sia_rangos set actual=inicio
ALTER TABLE sia_rangos ALTER COLUMN actual integer not null



drop table sia_rangos;
create table sia_rangos(
  idRango int identity(1,1) not null,
  token varchar(50) not null,
  descripcion varchar(100) not null,
  siglas varchar(50) not null,
  anio int not null,
  inicio int not null,
  fin int not null,
  disponible int not null,
  minimo int not null,
  siguiente int not null,
  usrAlta varchar(20) not null,
  fAlta datetime not null default getdate(),
  usrModificacion varchar(20) null,
  fModificacion datetime null,        
  estatus varchar(20) not null default 'ACTIVO'
);
ALTER TABLE sia_rangos ADD CONSTRAINT pk_rangos PRIMARY KEY (idRango);

INSERT INTO sia_rangos (nombre, anio, siglas, inicio, fin, siguiente, disponible, minimo, usrAlta, fAlta, estatus) 
VALUES('jose', '2015', 'ASCD-', 1, 2500, 1, 2500, 25, 'jacz', getdate(), 'ACTIVO');

Select * from sia_rangos
Select * from sia_rangosFolios

SELECT idRango id, nombre, anio, token, siglas, concat(inicio, ' al ', fin) rango, siguiente, disponible, minimo, estatus   FROM sia_rangos ORDER BY nombre

drop function obtenerFolio
 create function obtenerFolio (@token varchar(20) ) returns varchar(50)
 as
 begin 
   declare @resultado varchar(50)
   
    DECLARE @siguiente int;
    DECLARE @nomenclatura varchar(20);
    SELECT @siguiente = siguiente, @nomenclatura=siglas FROM sia_rangos WHERE token = @token;   
    --Update sia_rangos set siguiente= @siguiente +1  WHERE token = @token;  
   
   set @resultado=CONCAT(@nomenclatura, '-', @siguiente)
   return @resultado
 end;
 
 Select dbo.obtenerFolio('AUDITORIAS'), idRol from sia_roles
  Select * from sia_auditorias
 
Select * from sia_rangos 
 

drop PROCEDURE obtenerFolio
CREATE PROCEDURE obtenerFolio(@token nvarchar(20),@folio nvarchar(50) OUTPUT)
AS  
    SET NOCOUNT ON;
    declare @siglas varchar(50);
    declare @siguiente varchar(50);
    
    SELECT @siglas = siglas, @siguiente=siguiente FROM sia_rangos WHERE token = @token;
    set @folio = @siguiente
RETURN



exec dbo.obtenerFolio('NUM-AUDITORIAS')


Select * from sia_fasesactividades
Select * from sia_auditoriasactividades


---Lista de campos

--ALTER TABLE sia_cuentasingresos ALTER COLUMN usrModificacion varchar(20) NULL;

SELECT 'Alter table ' + OBJ.name + ' ALTER COLUMN ' +  COL.name + ' datetime NULL;' as SQL, 
    OBJ.name as tabla, ROW_NUMBER() OVER (ORDER BY COL.colid) AS id_columna, COL.name AS columna, TYP.name AS Tipo, 
        --Por algun motivo los nvarchar dan el doble de la longitud
        Longitud = CASE TYP.name 
            WHEN 'nvarchar' THEN COL.LENGTH/2
            WHEN 'varchar' THEN COL.LENGTH/2
            ELSE COL.LENGTH
            END,
        COL.xprec AS PRECISION, COL.xscale AS escala, COL.isnullable AS Isnullable, 
        FK.constid AS id_fk, OBJ2.name AS table_derecha, COL2.name 
        --COL.*
    FROM dbo.syscolumns COL JOIN dbo.sysobjects OBJ ON OBJ.id = COL.id JOIN dbo.systypes TYP ON TYP.xusertype = COL.xtype
    --left join dbo.sysconstraints CON on CON.colid = COL.colid
    LEFT JOIN dbo.sysforeignkeys FK ON FK.fkey = COL.colid AND FK.fkeyid=OBJ.id
    LEFT JOIN dbo.sysobjects OBJ2 ON OBJ2.id = FK.rkeyid
    LEFT JOIN dbo.syscolumns COL2 ON COL2.colid = FK.rkey AND COL2.id = OBJ2.id
    WHERE OBJ.name  like 'sia_%' and  COL.name in ('fModificacion', 'fAlta') and COL.isnullable='0'
    AND (OBJ.xtype='U' OR OBJ.xtype='V')
    Order by OBJ.name, COL.colid
    
Update sia_roles  set usrAlta=42 Where usrAlta='COTA'




    
    Select * from dbo.sysobjects  where name like 'sia_%'
    
---Alter table sia_auditoriasauditores add lider varchar(20) null 

Select t.name tabla, isnull(c.[name],'') columna, 'Alter table ' + t.name + ' add usrAlta int null, fAlta datetime null, usrModificacion int null, fModificacion datetime null;'
FROM sysobjects t  left join syscolumns c  on t.id = c.id  and c.name in ('usrAlta')
Where t.name like 'sia_%' and c.[name] is null 
Order by t.name, c.colorder

Select * from sia_usuarios;


Select * from sia_accesos


Select * from sysobjects Where name like 'sia_%'
Select * from syscolumns 


-- 25 de mayo 2016
Select * from sia_roles
Select * from sia_usuarios

Select * from sia_empleados 

Select idEmpleado, nombre, paterno, materno
from sia_empleados 
Where idEmpleado in (1291, 290, 2082, 1374, 1689, 1631, 430, 1332, 1568, 338, 1991, 1815, 1971, 2046, 2043, 2138, 2048, 2052)
order by nombre

ALTER TABLE sia_usuarios ALTER COLUMN telefono varchar(50) null
ALTER TABLE sia_usuarios ALTER COLUMN tipo varchar(50) null

Select * from sia_usuariosroles

-- 26 de mayo 2016

Select * from sia_fasesactividades
Select * from sia_auditorias
Select * from sia_auditoriasactividades

Select f.nombre, orden, min(aa.fInicio ) desde, max(aa.fFin) hasta
from sia_auditoriasactividades aa 
inner join sia_fases f on aa.idFase = f.idFase
Group by f.nombre, orden 
order by f.orden


alter table sia_fases add orden int null
ALTER TABLE sia_fasesactividades DROP COLUMN orden ;

---------

alter table sia_usuarios add saludo varchar(20) null




Select  idUsuario id, idEmpleado,  concat(saludo, ' ', nombre, ' ', paterno, ' ', materno) usuario, telefono, tipo, usuario cuenta,  estatus 
	FROM sia_usuarios Order by concat(nombre, ' ', paterno, ' ', materno);
  
  Update sia_usuarios set saludo='C.'

SELECT idUsuario id, saludo, idEmpleado, nombre, tipo, paterno, materno, telefono, usuario, pwd,  estatus FROM sia_usuarios WHERE idUsuario=42 
SELECT idUsuario id, saludo, idEmpleado, nombre, tipo, paterno, materno, telefono, usuario, pwd,  estatus FROM sia_usuarios ORDER BY TIPO

uPDATE sia_usuarios SET tipo='EMPLEADO' where idEmpleado is not null
uPDATE sia_usuarios SET tipo='OTRO' where idEmpleado='98'
uPDATE sia_usuarios SET usuario='alberto.sanchez' where idUsuario='98'

Select idRol id, nombre texto From sia_roles order by nombre  




Select t.name tabla, isnull(c.[name],'') columna
FROM sysobjects t  left join syscolumns c  on t.id = c.id  and c.name in ('lider')
Where t.name like 'sia_%' and c.[name] like '%lider%'
Order by t.name, c.colorder


-- PAPELES  DE TRABAJO


SELECT 
  p.idAuditoria auditoria,  s.nombre sujeto, o.nombre objeto, p.idPapel, p.tipoPapel, 
  p.tipoResultado, p.resultado, ta.nombre tipo,
		CONVERT(VARCHAR(12),p.fAlta,102) fechaPapel, p.idFase fase, p.tipoPapel, p.tipoResultado, p.resultado, p.estatus  
		FROM sia_papeles p   
		LEFT JOIN sia_sujetos s on p.idSujeto=s.idSujeto 
		LEFT JOIN sia_objetos o on p.idSujeto=o.idSujeto and p.idObjeto=o.idObjeto  
		LEFT JOIN sia_auditorias a on p.idAuditoria=a.idAuditoria 
		LEFT JOIN sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria 
 WHERE p.idCuenta='CTA-2014'
		ORDER BY  p.idPapel DESC
    
    Select * from sia_sujetos
    
    Select * from sia_unidades
    Select * from sia_auditorias
    Select * from sia_papeles
    
    Select * from sia_sectores
    drop table sia_papeles;
    
    Create table sia_papeles(
    idCuenta varchar(20) not null,
    idPrograma varchar(20) not null,
    idAuditoria varchar(20) not null,    
    idSector varchar(20) not null,
    idSubsector varchar(20) not null,
    idUnidad varchar(20) not null,
    idObjeto int,
    idFase  varchar(20) not null,
    idPapel int identity(1,1) not null,
    tipoPapel varchar(20) not null,
    tipoResultado varchar(20) not null,
    resultado varchar(200) not null,
    archivoOriginal varchar(100) not null,
    archivoFinal varchar(100) not null,
    usrAlta int null,
    fAlta datetime null default getdate(),
    usrModificacion int null,
    fModificacion datetime null,        
    estatus varchar(20) not null default 'ACTIVO'
    );
    
   
-- 27 de mayo
Select * from sia_auditoriasauditores order by idAuditoria
Select * from sia_auditoriasauditores Where idAuditoria='ASCM-20160420000900' order by idAuditor
Select * from sia_empleados order by idEmpleado
Select * from sia_plazas


  Select 
    Case When e.idEmpleado=aa.idAuditor  Then 'SI' Else 'NO' End As asignado,
    e.idEmpleado, aa.idAuditor,  e.idArea,
    concat(e.nombre, ' ', e.paterno, ' ', e.paterno) auditor, p.nombre plaza, 
    Case When aa.lider='SI' Then aa.lider Else '' End As lider
  from sia_empleados e 
  left join sia_auditoriasauditores aa on e.idEmpleado=aa.idAuditor  and aa.idAuditoria='ASCM-20160413203455'
  left join sia_plazas p on e.idPlaza=p.idPlaza
  Where e.idArea='DGACFC' and e.idNivel not in ('45.0', '40.0', '31.0')
  ORDER BY concat(e.nombre, ' ', e.paterno, ' ', e.paterno)



Select * from sia_plazas where idNivel='31.0'

Select * from sia_empleados Where idArea='DGACFC' order by idNivel desc
Select * from sia_objetos

alter table sia_objetos add idSector varchar(20) null,  idSubsector varchar(20) null, idUnidad varchar(20) null

Select idAuditoria, count(*) from sia_auditoriasauditores group by idAuditoria

Delete from sia_auditoriasauditores where idAuditor=1964


Select *  from sia_auditorias 
Select *  from sia_auditorias 
alter table sia_auditorias add idSector varchar(20) null,  idSubsector varchar(20) null, idUnidad varchar(20) null

ALTER TABLE sia_empleados DROP CONSTRAINT pk_empleados; 
ALTER TABLE sia_empleados ALTER COLUMN idEmpleado  int  not NULL;

ALTER TABLE sia_auditoriasauditores DROP CONSTRAINT pk_audi1audi2; 
ALTER TABLE sia_auditoriasauditores ALTER COLUMN idAuditor  int  not NULL;


Select e.idEmpleado, aa.idAuditor, e.idPlaza, e.idNivel, e.idArea
from sia_empleados e 
  left join sia_auditoriasauditores aa on e.idEmpleado=aa.idAuditor    
  inner join sia_plazas p on e.idPlaza=p.idPlaza
  
Where e.idArea='DGACFC' and e.idNivel not in ('45.0', '40.0', '31.0')

Delete from sia_auditoriasauditores
Select * from sia_auditoriasauditores

---- 

Select * from sia_objetos
Select * from sia_papeles

-- Lista de Centro gestor
Select concat(u.idSector, '-', u.idSubsector, '-', u.idUnidad) id, u.nombre texto  
from sia_unidades u 
inner join sia_areasunidades au on u.idCuenta = au.idCuenta and au.idSector = u.idSector and u.idSubsector = au.idSubsector and au.idUnidad = u.idUnidad
Where u.idCuenta = 'CTA-2014' and au.idArea='DGACFC'

-- Lista de objetos x Centro gestor

01-CD-03
01-CD-04
01-CD-05


Select * from sia_objetos
Where concat(idSector, '-', idSubsector, '-', idUnidad)='01-CD-05'

Select * from sia_objetos Where idCuenta='CTA-2014' and concat(idSector, '-', idSubsector, '-', idUnidad)='01-CD-05'

Drop TABLE sia_objetos;

CREATE TABLE sia_objetos(
idCuenta varchar(20) NOT NULL,
idPrograma varchar(20) NOT NULL,
idAuditoria varchar(20) NOT NULL,
idObjeto  int identity(1,1) NOT NULL,
nombre varchar(200) NOT NULL,
nivel varchar(20) NOT NULL,
usrAlta int not NULL,
fAlta datetime NOT NULL,
usrModificacion int NULL,
fModificacion datetime NULL,
estatus varchar(20) NOT NULL default 'ACTIVO'
);
ALTER TABLE sia_objetos ADD CONSTRAINT pk_objs PRIMARY KEY (idCuenta, idPrograma, idAuditoria, idObjeto);
ALTER TABLE sia_objetos  ADD FOREIGN KEY (idCuenta, idPrograma, idAuditoria ) REFERENCES sia_auditorias(idCuenta, idPrograma, idAuditoria);

Drop TABLE sia_objetosDetalles
CREATE TABLE sia_objetosDetalles(
idCuenta varchar(20) NOT NULL,
idPrograma varchar(20) NOT NULL,
idAuditoria varchar(20) NOT NULL,
idObjeto int NOT NULL,
idObjetoDetalle varchar(20) not null,
nombre varchar(200) NOT NULL,
usrAlta int not NULL,
fAlta datetime NOT NULL,
usrModificacion int NULL,
fModificacion datetime NULL,
estatus varchar(20) NOT NULL default 'ACTIVO'
);
ALTER TABLE sia_objetosDetalles ADD CONSTRAINT pk_objsDet PRIMARY KEY (idCuenta, idPrograma, idAuditoria, idObjeto, idObjetoDetalle);
ALTER TABLE sia_objetosDetalles  ADD FOREIGN KEY (idCuenta, idPrograma, idAuditoria, idObjeto ) REFERENCES sia_objetos(idCuenta, idPrograma, idAuditoria, idObjeto);




Select * from sia_cuentasingresos
Select * from sia_cuentasdetalles





