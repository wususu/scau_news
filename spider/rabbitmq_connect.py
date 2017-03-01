from hmt_news.db_config import *
import pymysql
from hmt_news.jwc_spider import *
import hashlib


def sove_data():
    spider = JWCSpider()
    spider.text()
    global n
    global p
    n = spider.get_n_news()
    p = spider.get_p_news()
    for i in n:
        md5 = hashlib.md5()
        md5.update((i['href'] + i['title']).encode(encoding='utf8'))
        i['md5'] = md5.hexdigest()
    for i in p:
        md5 = hashlib.md5()
        md5.update((i['href'] + i['title']).encode(encoding='utf8'))
        i['md5'] = md5.hexdigest()


def into_db():
    conn = pymysql.connect(host=host, port=port, user=user, password=password, db=db, charset='utf8')
    cursor = conn.cursor()
    sql = "insert into news (title, href, md5, origin, news_time) VALUES (%s, %s, %s, %s, %s)"
    try:
        flag = 0
        for i in n:
            try:
                cursor.execute(sql, (i['title'], i['href'], i['md5'], 1, i['time']))
                conn.commit()
            except pymysql.err.IntegrityError:
                flag+=1
                pass
        for i in p:
            try:
                cursor.execute(sql, (i['title'], i['href'], i['md5'], 2, i['time']))
                conn.commit()
                print('ok')
            except pymysql.err.IntegrityError:
                flag+=1
                pass
    finally:
        conn.close()
        print(flag)


if __name__ == '__main__':
    sove_data()
    into_db()