#!/bin/bash
#Author: Aygun Aydin & Tuba Orhan & Neval Goksel 22.12.2016
#Update 22.12.2016 11:45: mail atan satirlar silindi. Aygun 22.12.2016

#Calismakta olan docker processlerinin sayisini buluyoruz
count=`docker ps | grep dbkedi | wc -l`
    
#Eger process sayisi 1'den kucukse processleri restart ediyoruz
if [ $count -lt 1 ]
then
        docker stop turuncukedi;
 		sleep 4
 	docker stop lindpress;
		sleep 5
        docker stop morkedi;
		sleep 5
        docker start dbkedi;
		sleep 5
        docker start morkedi;
		sleep 5
        docker start turuncukedi;
		sleep 5
	docker start lindpress;		
	#log dosyasina hata kaydi atiliyor
	echo "`date` ERROR: db ayakta degil tum processler restart edildi" >> /tmp/apponyControl.log
	
#Eger process sayisi 1'den buyukse hata olmadigina dair log atiliyor
else
#calisan prosesslerin ismini aliyor
	pronames=`docker ps | grep -v "^CONTAINER" | awk '{print $NF","}' | tr -d '\n'`;
        echo "`date` INFO: db ayakta sorun yok ve calisan processler "${pronames::-1}"" >> /tmp/apponyControl.log
fi
