# 本系统仅Web部分开源，其他不开源，是商业软件。介意的勿扰
# 质量更好，运行更稳定，功能更多，价格更低，不拿功能区分版本，版本就这一个。

# OpenNVR

#### 基于OpenRTMP构建
github   https://github.com/WilsonDhChen/OpenRTMP  
国内下载   https://gitee.com/open-nvr/OpenRTMP  
#### 飞腾，鲲鹏arm版本请直接联系作者  
#### 介绍
OpenNVR简介

是一款传统视频监控网络与现代互联网视频融合的产品，打通了传统监控网络与现代互联网视频的通道，能够分发监控网络视频到互联网直播，也可以采集互联网直播源进入传统监控网络。存储功能支持分布式存储和传统硬盘存储。

采用业界优秀的流媒体框架模式设计，服务运行轻量、高效、稳定、可靠、易维护。视频能够接入Web、Android、iOS、微信 等全平台客户端，是移动互联网时代一款接地气的流媒体服务器，满足用户在各种行业场景的流媒体业务需求。可选择的不同模型。

支持多CPU多核心，采用并行流水线架构，定制专用Linux核心充分发挥系统性能，显著的提高了网络IO性能最高达网卡91%，为客户省去大笔硬件成本减少服务器部署数量。
支持负载均衡/集群
支持本地多硬盘分片存储，硬盘越多写入速度越快，是一种介于RAID5与RAID10之间高性价比方案。
采用TS作为存储格式，服务器突然断电，硬盘恢复，都能使视频顺利播放。
全网络互联，视频融合。
支持二次开发提供丰富的API调用，为第三方应用提供可靠的视频源。

QQ交流群：777901741  
github   https://github.com/WilsonDhChen/opennvr  
国内下载   https://gitee.com/open-nvr/opennvr  

  
#### 软件架构
1） 支持多CPU多核心，采用并行流水线架构(CPU核心越多流水线级别越多，高并发60 FPS不卡顿)，多nb的硬件都能完全发挥性能。  
2） 7x24 全天候稳定运行。  
3）Intel E3 CPU ，10Gb网卡,RTMP稳定输出 8.8Gb/s，HLS稳定输出9Gb/s，稳定并发8K连接 ，CPU 占用30%  
4）支持视频秒开,出画面速度0.2-0.3s  
5）支持master/slave,自动请求master  
6）支持负载均衡/集群  
7）支持CDN部署(最大2W节点小型CDN)，部署简单  
8）支持3台控制服务器热备，任意两台出问题不影响服务  
9）支持Flash Player推送  
10）行业内第一个支持虚拟直播的服务器，RTMP信号源自由切换，播放不间断(可做电影频道，插播广告)  
11）ONVIF support, PTZ support with lua  
12）GB/T28181-2011/2016(支持通过海康私有协议与海康平台传视频，视频输出更稳定) input/output  
13）录制回放支持多盘高速读写，硬盘写入与网卡完美匹配，支持分布式存储，可定制各种存储接口  
14）单台服务器支持域名隔离  
15）支持分布式文字互动（数据格式用户自定义），也可以当聊天室使用  
   
关于并发：   
程序并发没有任何限制，但是实际并发数取决于网卡的速率和CPU的性能以及视频码率。期待大家拿更NB的机器测试，性能强劲的机器需要优化mediasrv.ini发挥多CPU性能(这样的设计可以在不支持fork的windows上支持高并发)  
  
支持的协议：   
1）输入协议，RTMP/RTSP/GB28181   
2）输出协议，RTMP RTMPT HLS(Memory file support) RTSP HTTP-FLV(support video/audio only) HTTP-TS(支持混合一路GPS数据) HTTP-AAC GB28181 UDP组播输出  
以上所有协议都支持SSL传输  
3）WebRTC SFU支持(兼容Wowza Websocket播放协议)，音频支持OPUS g.711(PCMA/PCMU)  

支持lua(5.3)：  
1）lua脚本在mediasrv.lua中，里面对所有的API函数做了demo,请参考调用示例 
2）支持推送前鉴权 ,设置是否录制 TS/FLV/MP4  
3）支持推送成功通知  
4）支持推送关闭通知  
5）支持播放前鉴权  
6）支持播放关闭后通知  
7）支持录制结束通知  
8）支持CDN源站地址获取  
9）支持会话流量获取  
  
RTSP 推送地址：(H265 support)  
  
./ffmpeg.exe -rtsp_transport tcp -i rtsp://192.168.1.161/ -vcodec copy -acodec copy -rtsp_transport tcp -f rtsp rtsp://127.0.0.1/gb28181/chid  

RTMP 推送地址：  
  
RTMP rtmp://127.0.0.1/gb28181/chid  
RTMP rtmp://127.0.0.1:2935/gb28181/chid  
  
访问地址示例：  
  
HLS http://127.0.0.1:280/gb28181/chid.m3u8 (H265 support)  
RTMP rtmp://127.0.0.1/gb28181/chid  
RTSP rtsp://127.0.0.1/gb28181/chid (H265 support)  
HTTP-TS http://127.0.0.1:280/gb28181/chid.ts (H265 support)  
HTTP-FLV http://127.0.0.1:280/gb28181/chid.flv  
HTTP-AAC http://127.0.0.1:280/gb28181/chid.aac  
视频快照  
http://127.0.0.1:280/gb28181/chid.snapshot  
  
  
#### 安装教程

1. 系统要求 Centos 7
2. 存储盘和系统盘要分开
3. 下载后执行安装脚本 sh ./install
4. 默认用户名 admin 123456






