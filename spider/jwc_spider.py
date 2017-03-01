from bs4 import BeautifulSoup
import requests
import re


class JWCSpider():
    """
    # 教务处爬虫
    """
    def __init__(self):
        # 教务处公告
        self.__p_origin_url = 'http://jwc.scau.edu.cn/jwc/index.php?m=content&c=index&a=lists&catid=16'
        # 教务处新闻
        self.__n_origin_url = 'http://jwc.scau.edu.cn/jwc/index.php?m=content&c=index&a=lists&catid=15'
        self.__p_urls = self.__get_p_urls()
        self.__n_urls = self.__get_n_urls()

    def __get_html(self, url):
        """
        :param url: 教务页面url
        :return： 页面html代码/False
        """
        resp = requests.get(url)
        if resp.status_code == 200:
            html = BeautifulSoup(resp.content, 'html.parser')
            return html
        return False

    def __get_urls(self, html):
        tags = html.find('div', class_=['list', 'w736', 'fl', 'border']).find_all('li')
        if tags != None:
            temp_list = list()
            for tag in tags:
                temp_dict = dict()
                tag_a = tag.find('a')
                tag_span = tag.find('span')
                href = tag_a['href']
                title = tag_a.get_text()
                print('href: ', href)
                print('title: ', title)
                temp_dict['href'] = href
                temp_dict['title'] = title
                temp_list.append(temp_dict)
            return temp_list
        return False

    def __get_news_time(self, news_url):
        html = self.__get_html(news_url)
        # 教务处时间戳
        try:
            tags = html.find('div', class_='detail_title')
            time_tag = tags.find_all('span')
            if time_tag != None:
                time = time_tag[1].text
                return time
        except Exception:
            # 华农官网时间戳
            tags = html.find('table', attrs={'width':'880', 'border':'0', 'cellspacing':'0'})
            if tags != None:
                time = tags.find('td', attrs={'align': 'center'}, text=re.compile('(.*?)撰写时间(.+?)')).text
                time = re.findall('(.*?)撰写时间\:(\d\d\d\d\-\d\d\-\d\d.*?)(.+?)', time)
                if len(time) != 0:
                    time = time[0][1] + ' 00:00:00'
                # print("second catch: ",time)
                return time
            # 通用时间戳(有时间)
            tags = html.find(text=re.compile('(.?)(\d\d\d\d\-\d\d\-\d\d\s\d\d\:\d\d\:\d\d)(.+?)'))
            if tags != None:
                time = re.findall('(.*?)(\d\d\d\d\-\d\d\-\d\d\s\d\d\:\d\d\:\d\d)(.*?)', tags)
                time = time[0][1]
                return time
            # 通用时间戳（无时间）
            tags = html.find(text=re.compile('(.?)(\d\d\d\d\-\d\d\-\d\d)(.+?)'))
            if tags != None:
                time = re.findall('(.?)(\d\d\d\d\-\d\d\-\d\d)(.+?)', tags)
                time = time[0][1] + ' 00:00:00'
                return time
        return False

    def __get_p_urls(self):
        html = self.__get_html(self.__p_origin_url)
        urls = self.__get_urls(html)
        if len(urls) != 0:
            return urls
        return False

    def __get_n_urls(self):
        html = self.__get_html(self.__n_origin_url)
        urls = self.__get_urls(html)
        if len(urls) != 0:
            return urls
        return False

    def text(self):
        print(self.__p_urls)
        print(self.__n_urls)
        for i in self.__n_urls:
            url = i['href']
            time = self.__get_news_time(url)
            i['time'] = time
            print(time)
        for i in self.__p_urls:
            url = i['href']
            time = self.__get_news_time(url)
            i['time'] = time
        print(self.__p_urls)
        print(self.__n_urls)

    def get_n_news(self):
        return self.__n_urls

    def get_p_news(self):
        return self.__p_urls

# if __name__ == '__main__':
#     instance = JWCSpider()
#     instance.text()
