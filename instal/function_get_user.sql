 SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = outils, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;
 
CREATE FUNCTION outils.get_user() RETURNS integer
    LANGUAGE plpgsql STABLE
    AS $$
declare
ergebnis integer;
    BEGIN
        perform relname from pg_class
            where relname = 'icke_tmp'
              and case when has_schema_privilege(relnamespace, 'USAGE')
                    then pg_table_is_visible(oid) else false end;
  if not found then
    return 0;
  else
    select id_user from icke_tmp into ergebnis;
  end if;
  if not found then
    ergebnis:= 0;
  end if;
  RETURN ergebnis;
  END;
 $$;
