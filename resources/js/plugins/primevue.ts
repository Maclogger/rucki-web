import {definePreset} from '@primeuix/themes';
import Lara from '@primeuix/themes/lara';


export const myPreset = definePreset(
    Lara, {
        semantic: {
            primary: {
                50: '#e9e9e9',
                100: '#d4d4d4',
                200: '#a9a9a9',
                300: '#7f7f7f',
                400: '#545454',
                500: '#2A2A2A',
                600: '#212121',
                700: '#191919',
                800: '#101010',
                900: '#080808',
                950: '#040404',
            },
            secondary: {

            }
        },
        colorScheme: {
            light: {
                primary: {
                    color: '#2A2A2A',
                    inverseColor: '#ffffff',
                    hoverColor: '{zinc.900}',
                    activeColor: '{zinc.800}'
                },
                highlight: {
                    background: '{zinc.950}',
                    focusBackground: '{zinc.700}',
                    color: '#ffffff',
                    focusColor: '#ffffff'
                },
            },
            dark: {
                primary: {
                    color: '{zinc.50}',
                    inverseColor: '{zinc.950}',
                    hoverColor: '{zinc.100}',
                    activeColor: '{zinc.200}'
                },
                highlight: {
                    background: 'rgba(250, 250, 250, .16)',
                    focusBackground: 'rgba(250, 250, 250, .24)',
                    color: 'rgba(255,255,255,.87)',
                    focusColor: 'rgba(255,255,255,.87)'
                }
            }
        },
        components: {
            button: {
                colorScheme: {
                    light: {
                        root: {

                        }
                    },
                    dark: {

                    },
                }
            }
        }
    })
