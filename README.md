# Nuptic-77

In the year 2077, humanity has found an exoplanet (NUPTIC-77) with evidence of
inorganic life based on artificial intelligence and inhabited by threads seeking to
establish communication with other planets through HTTP requests.

## Requirements
1. Develop a NUPTIC-77 simulator.
2. Develop a server to provide a response to each HTTP request.
3. Develop an interface to visualize the simulation and the resulting report.

## How to run the project

The project is made to run on docker.

### Configuration
1. Copy the file `application\.env.dist` to `application\.env`
2. Copy the file `docker\.env.dist` to `docker\.env`
3. Copy the file `docker\docker-compose.yml.dist` to `docker\docker-compose.yml`

Run the app from docker folder with `docker compose up -d`

If the configuration is not changed access to `http://localhost:8080/simulator.html` to start the simulation. Push start and the request will start. During the run the graphics will update each two seconds, resume data will be shown at the end.

#### Entering containers

From the docker folder you'll be able to access docker containers

###### Php

Run `docker compose exec php bash`
From here you'll be able to access the logs. Requests with `East` direction are stored in `/application/var/log/dev.direction.log`

###### Redis
Run `docker compose exec redis bash`
`redis-cli` is installed, so you can check cached data.
1. `KEYS *` will show all the stored keys
2. `get requests_of_yyyymmdd` will show the amount of request done the selected date.
3. `get resumeDatayyyymmdd` will show the resume of the process. Amount of repetitions in each direction and total of route.
4. `get graphicsData_yyyymmdd` will show the information to draw the graphics.

*Date example 20221030*

## Additional info
If you are using some of the jetbrains IDE there are two additional files to interact with the app. They are in `/application/tests/http-requests/` with the default configuration data you can click the play button and see the return. If you change the exposed app port, you should modify these files.