### Contribution

If you use Docker, setup local environment with the following command:
```bash
$ docker-compose up -d --build
```

Then enter the app container:
```bash
$ docker-compose exec app sh
```

And run the following command:
```bash
$ make init
```
