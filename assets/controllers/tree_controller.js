import {Controller} from '@hotwired/stimulus';
import {Treant} from "treant-js";
import "treant-js/Treant.css";

window.Raphael = require('treant-js/vendor/raphael');

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
        items: Object
    }

    connect() {
        console.group('tree');
        console.log('hello, i am a tree', this.element);
        console.dir(this.itemsValue);
        console.groupEnd();

        let config = {
            chart: {
                container: "#tree-simple",
                nodeAlign: "BOTTOM",
                connectors: {
                    type: 'step'
                },
                node: {
                    collapsable: false
                }
            },
            nodeStructure: {
                text: {
                    name: "root"
                },
                children: []
            }
        }

        config.nodeStructure = this.itemsValue;

        /*
        this.itemsValue.forEach((value, index) => {
            config.nodeStructure.children.push({
                text: {
                    name: value.name
                },
                children: []
            });
            config.nodeStructure.children.children.forEach((v, i) => {
                config.nodeStructure.children.push({
                    text: {
                        name: v.text.name
                    },
                    children: []
                });
            });
        });
        console.dir(config);
         */

        let my_chart = new Treant(config, function () {
            console.dir('Tree Loaded')
        });
    }

    init() {
        console.dir(this.configValue);
        console.dir(this.config);

    }
}
