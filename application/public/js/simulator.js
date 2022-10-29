nupticSimulationInProgress = false;

class RequestSimulator {
    static requestsInProgress = false;
    #URL = 'http://localhost:8080/nuptic-77';
    #MAX_REQUESTS = 60;
    #DIRECTIONS = ['North', 'East', 'West', 'South'];
    #MIN_ROUTE = 10;
    #MAX_ROUTE = 20;

    async start () {
        RequestSimulator.requestsInProgress = true;
        let response = null;
        for (let num = 1; num <= this.#MAX_REQUESTS; num++) {
            let data = {
                'simulator_id': 'simulator_id_test',
                num,
                'direction': this.#getRandomDirection(),
                'route': this.#getRandomRoute(),
            }
            response = await this.#doCall(data);
            await sleep(1000);
        }
        RequestSimulator.requestsInProgress = false;
        this.#printResumeData(response['data']['resume_data']['Route'], response['data']['resume_data']['Direction']);
    }

    #getRandomDirection () {
        return this.#DIRECTIONS[Math.floor(Math.random() * 4)];
    }

    #getRandomRoute() {
        return Math.floor(Math.random() * (this.#MAX_ROUTE - this.#MIN_ROUTE +1)) + this.#MIN_ROUTE;
    }

    #doCall(data) {
        return fetch(this.#URL, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }
        ).then( response => response.json() )
            .then( json => json )
            .catch( error => {
                console.log(error);
                this.#doCall(data);
            })
            ;

    }

    #printResumeData(route, direction) {
        let resume = document.getElementById('resume_data');
        let h3 = document.createElement('h3');
        h3.innerText = 'Resume:';
        resume.appendChild(h3);
        let data = document.createElement('p')
        data.innerText = `Route total: ${route}
        The most frequent address: ${direction}`;
        resume.appendChild(data);
    }
}

class GraphicInfo {
    #DELAY = 2000;
    #URL = 'http://localhost:8080/nuptic-77/graphs-data';
    #routeChart = null;
    #directionChart = null;
    async start () {
        do{
            this.#printGraphicsInfo();
            await sleep(this.#DELAY);
        }while(RequestSimulator.requestsInProgress);
        this.#printGraphicsInfo();
    }

    async #printGraphicsInfo() {
        if(this.#routeChart !== null) {
            this.#routeChart.destroy();
        }
        if(this.#directionChart !== null) {
            this.#directionChart.destroy();
        }
        let response = await fetch(this.#URL, {method: 'get'}).then(response => response.json());
        let routeInfo = this.#getRouteConfig(response['data']['Route'], 'line', 'Graphic Route');
        let directionInfo = this.#getRouteConfig(response['data']['Direction'], 'bar', 'Graphic direction');

        this.#routeChart = new Chart(
            document.getElementById('route'),
            routeInfo
        );

        this.#directionChart = new Chart(
            document.getElementById('direction'),
            directionInfo,
        );
    }

    #getRouteConfig(simulationInfo, graphType, graphName) {
        let labels = [];
        let routeData = [];
        for(let key in simulationInfo)
        {
            labels.push(key);
            routeData.push(simulationInfo[key]);
        }

        const data = {
            labels: labels,
            datasets: [{
                label: graphName,
                data: routeData,
            }]
        };
        return {
            type: graphType,
            data: data,
            options: {
                responsive: true
            }
        };
    }
}

function startSimulation() {
    let simulator = new RequestSimulator();
    simulator.start();
    sleep(1000);
    let graphics = new GraphicInfo();
    graphics.start();
}