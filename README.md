# IspMonitor

Let's face it, my ISP has it's issues. I wanted a way to show that to them the next time I call. Like much of what I do, I created an open-source project to make this. I will provide better documentation later, but here is an overview of how it works:

1. RobotUptime pings my home IP every 5 minutes.
2. Every 4 hours, a cron job will pull the data from RobotUptime via their API and add it to a database. For some reason I was getting lots of 0 ping times. So I removed them for now.
3. I query the data and show the past 24 hours worth of data.


### Future features

* Add the ability to go back to previous days.
