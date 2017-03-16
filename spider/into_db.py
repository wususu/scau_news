from db_config import *
import pymysql
import jwc_spider
import hashlib
from log import my_log


def sove_data():
    """
    md5 加密项
    :return:
    """
    spider = jwc_spider.JWCSpider()
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
    """
    数据入库
    :return:
    """
    conn = pymysql.connect(host=host, port=port, user=user, password=password, db=db, charset='utf8')
    cursor = conn.cursor()
    sql = "insert into news (title, href, md5, origin, news_time) VALUES (%s, %s, %s, %s, %s)"
    try:
        flag_n = 0
        flag_p = 0
        for i in n:
            try:
                cursor.execute(sql, (i['title'], i['href'], i['md5'], 1, i['time']))
                conn.commit()
            except pymysql.err.IntegrityError:
                flag_n += 1
                pass
        for i in p:
            try:
                cursor.execute(sql, (i['title'], i['href'], i['md5'], 2, i['time']))
                conn.commit()
                print('ok')
            except pymysql.err.IntegrityError:
                flag_p += 1
                pass
    finally:
        conn.close()
        count_p = len(p)
        count_n = len(n)
        message_n = '共爬取',count_n,'条教务处【新闻】： ',count_n-flag_n,' 条更新 | ',flag_n,' 条重复'
        message_p = '共爬取',count_p,'条教务处【公告】： ',count_p-flag_p,' 条更新 | ',flag_n,' 条重复'
        print(message_n)
        print(message_p)
        my_log('info', message_n)
        my_log('info', message_p)


if __name__ == '__main__':
    sove_data()
    into_db()