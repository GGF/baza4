INSERT INTO `zaomppsklads`.sk_him1__dvizh
                (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                SELECT sk_him1__dvizh_arc.type,sk_him1__dvizh_arc.numd,sk_him1__dvizh_arc.numdf,sk_him1__dvizh_arc.docyr,sk_him1__dvizh_arc.spr_id,sk_him1__dvizh_arc.quant,sk_him1__dvizh_arc.ddate,sk_him1__dvizh_arc.post_id,sk_him1__dvizh_arc.comment_id,sk_him1__dvizh_arc.price
                FROM `zaomppsklads`.sk_him1__dvizh_arc WHERE YEAR(ddate)='2012';
DELETE FROM `zaomppsklads`.sk_him1__dvizh_arc WHERE YEAR(ddate)='2012'