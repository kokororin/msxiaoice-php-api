<?php
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class XiaoIceAPI
{
    private $headers = [];

    public function __construct($headers)
    {
        $this->initHeaders($headers);
    }

    private function initHeaders($headers)
    {
        foreach (explode(PHP_EOL, $headers) as $line) {
            if (trim($line) == '') {
                continue;
            }
            list($key, $value) = explode(':', $line, 2);
            if (trim($key) == '') {
                continue;
            }
            $this->headers[trim($key)] = $value;
        }

        unset($this->headers['Host']);
    }

    public function chat($query)
    {
        $data = [
            'location' => 'msgdialog',
            'module' => 'msgissue',
            'style_id' => 1,
            'text' => $query,
            'uid' => 5175429989,
            'tovfids' => '',
            'fids' => '',
            'el' => '[object HTMLDivElement]',
            '_t' => 0,
        ];

        $client = new HttpClient();
        try {
            $response = $client->post('https://www.weibo.com/aj/message/add?ajwvr=6', [
                'form_params' => $data,
                'headers' => $this->headers,
            ]);
            $result = (string) $response->getBody();
            $result = json_decode($result, true);
            if ($result['code'] == '100000') {
                return $this->dicts('success', $this->loop($query));
            } else {
                return $this->dicts('fail', $result['msg']);
            }

        } catch (Exception $e) {
            $this->dicts('fail', $e->getMessage());
        }
    }

    private function loop($query)
    {
        $times = 1;

        while ($times) {
            $times++;

            $client = new HttpClient();
            $response = $client->get('https://www.weibo.com/aj/message/getbyid?ajwvr=6&uid=5175429989&count=1&_t=0', [
                'headers' => $this->headers,
            ]);
            $result = (string) $response->getBody();
            $result = json_decode($result, true);
            $html = $result['data']['html'];
            $crawler = new Crawler();
            $crawler->addHtmlContent($html);

            $crawler = $crawler->filter('p.page');

            $reply = '抓取小冰结果超时啦QwQ';

            try {
                $reply = $crawler->text();
            } catch (Exception $e) {}

            if ($reply != $query || $times > 20) {
                break;
            }

            usleep(0.3 * 1000000);
        }

        return $reply;

    }

    private function dicts($status, $text)
    {
        return [
            'status' => $status,
            'text' => $text,
        ];
    }

}
