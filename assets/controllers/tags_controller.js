import {Controller} from '@hotwired/stimulus';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import "tabulator-tables/dist/css/tabulator_midnight.min.css";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {

    items = [];
    raw = [];
    config = {};

    static values = {
        tags: Array
    }

    connect() {
        this.createTable();

        document.getElementById('table_tags_search').addEventListener('input', this.search);
    }

    createTable() {
        //custom formatter definition
        let deleteButton = function (cell, formatterParams, onRendered) { //plain text value
            let html = "<span class='fa-solid fa-trash text-warning'></span>";
            // cell.getElement().style.backgroundColor = '#a20948';
            return html;
        };

        //create Tabulator on DOM element with id "example-table"
        this.table = new Tabulator("#table_tags", {
            height: 205, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
            data: this.tagsValue, //assign data to table
            layout: "fitColumns", //fit columns to width of table (optional)
            columns: [ //Define Table Columns
                {title: "Name", field: "name", width: 150},
                {title: "Description", field: "description", hozAlign: "left", },
                // {title: "Catégorie", field: "category", sorter: "date", hozAlign: "center"},
                {
                    formatter: deleteButton, width: 40, headerSort: false, hozAlign: "center", cellClick: (e, cell) => {
                        if (window.confirm('Supprimer le tag [' + cell.getRow().getData().name + '] ?')) {
                            this.deleteRow(cell.getRow());
                            alert("It's treason then: " + cell.getRow().getData().name);
                        }
                    }
                },
            ],
        });

        //trigger an alert message when the row is clicked
        this.table.on("rowClick", (e, row) => {
            this.fillForm(row.getData());
        });
    }

    search(event) {
        let table = Tabulator.findTable("#table_tags")[0]; // find table object for table with id of example-table
        table.setFilter('name', 'like', event.target.value);
    }

    fillForm(data) {
        console.dir(data);
        document.getElementById('tag_id').value = data.id;
        document.getElementById('tag_name').value = data.name;
        document.getElementById('tag_description').value = data.description;
    }

    deleteRow(row) {
        this.delete(row.getData());
        // If you already have the RowComponent for the row you wish to delete, you can call the delete function directly on the component:
        row.delete();
    }

    delete(item) {
        // Example POST method implementation:
        async function postData(url = '', data = {}) {
            // Default options are marked with *
            const response = await fetch(url, {
                method: 'DELETE', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                    'Content-Type': 'application/json'
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify(data) // body data type must match "Content-Type" header
            });
            return response.json(); // parses JSON response into native JavaScript objects
        }

        postData('http://niwa-api:80/api/tag/delete/' + item.id, {answer: 42})
            .then(data => {
            });

    }
}
