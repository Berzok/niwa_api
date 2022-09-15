import { Application } from 'stimulus';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { definitionsFromContext } from 'stimulus/webpack-helpers'

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
//export const app = startStimulusApp(require.context(
const context = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));
context.debug = process.env.APP_ENV === 'dev';

export const app = context;

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
// app.register('some_controller_name', SomeImportedController);

// enable Stimulus debug mode in development
