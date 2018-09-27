SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = outils, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE FUNCTION outils.set_user(myid_user integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
    BEGIN
        perform relname from pg_class
            where relname = 'icke_tmp'
              and case when has_schema_privilege(relnamespace, 'USAGE')
                    then pg_table_is_visible(oid) else false end;
        if not found then
            create temporary table icke_tmp (
                id_user integer
            );
        else
           delete from icke_tmp;
        end if;

        insert into icke_tmp values (myid_user);
  RETURN 0;
  END;
 $$;

