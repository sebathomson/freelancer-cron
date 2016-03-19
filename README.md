freelancer-cron
============

Set of commands to be executed by a cronjob, for sending emails with new offers **freelancer.cl**.

All commands must be execute with `php app/console`.

`freelancer:check:status` Check the status of this system
`freelancer:reset:log` Reset the log of offers sended
`freelancer:scrap:query` Command to get all publications of the query (default is: "Symfony")
`freelancer:scrap:skill` Command to get all publications of some id skills (default is: "385" and delimiter is ",")

## PARAMETERS

     # ../app/config/config.yml
        parameters:
            freelancer.url: 'https://www.freelancer.cl'
            freelancer.url_scrap: '%freelancer.url%/ajax/table/project_contest_datatable.php'
            freelancer.message_title: 'Last offers @type@: @arguments@'
            freelancer.message_error: 'System Down!'
            freelancer.path_log: "%kernel.logs_dir%/freelancer_sends.log"

You can contact me by email: **seba.thomson@gmail.com**

## LICENCE

**MIT**

> Written with [StackEdit](https://stackedit.io/).