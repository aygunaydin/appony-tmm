#!/bin/bash
#Update 22.12.2016 11:45: mail atan satirlar silindi. Aygun 22.12.2016

    
 docker stop dbkedi;
                sleep 4        
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
	
