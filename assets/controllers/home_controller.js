import {Controller} from '@hotwired/stimulus';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import ky from 'ky';
import "tabulator-tables/dist/css/tabulator_midnight.min.css";

const api = ky.create({
    prefixUrl: 'api'
});

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

    connect() {
        console.dir('I am here');

        document.getElementById('search_input').addEventListener('keyup', this.search)
    }

    /**
     * Delete a resource
     * @param event
     * @returns {Promise<void>}
     */
    async deleteResource(event) {
        const id = event.target.dataset.id;
        const json = await api.delete('resource/delete/' + id);
        console.dir(json);
        //location.reload();
    }

    search(e){
        let term = e.target.value;
        let items = [];
        let nodes = document.querySelectorAll('li.folder, li.resource');
        nodes.forEach((value, key) => {
            items.push({
                name: value.firstElementChild.children[0].innerText.trim(),
                node: value
            });
        });
        items.forEach((item) => {
            item.node.hidden = !item.name.includes(term);
        });
    }
}
