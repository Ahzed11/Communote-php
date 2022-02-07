module.exports = {
  purge: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
  ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {
      backgroundColor: ['odd', 'even'],
    },
  },
  plugins: [
    require('daisyui'),
  ],
  important: true,
  daisyui: {
    themes: [
      {
        'light': {
          'primary': '#6D28D9',
          'primary-focus' : '#6D28D9',     /* Primary color - focused */
          'primary-content' : '#ffffff',   /* Foreground content color to use on primary color */

          'secondary' : '#DDD6FE',         /* Secondary color */
          'secondary-focus' : '#DDD6FE',   /* Secondary color - focused */
          'secondary-content' : '#6D28D9', /* Foreground content color to use on secondary color */

          'accent' : '#37cdbe',            /* Accent color */
          'accent-focus' : '#2aa79b',      /* Accent color - focused */
          'accent-content' : '#FECACA',    /* Foreground content color to use on accent color */

          'neutral' : '#ffffff',           /* Neutral color */
          'neutral-focus' : '#f5f5f5',     /* Neutral color - focused */
          'neutral-content' : '#363636',     /* Foreground content color to use on neutral color */

          'base-100' : '#ffffff',          /* Base color of page, used for blank backgrounds */
          'base-200' : '#f9fafb',          /* Base color, a little darker */
          'base-300' : '#d1d5db',          /* Base color, even more darker */
          'base-content' : '#1f2937',     /* Foreground content color to use on base color */

          'info' : '#2094f3',              /* Info */
          'success' : '#009485',           /* Success */
          'warning' : '#FDE68A',           /* Warning */
          'error' : '#B91C1C',
        },
      },
      {
        'dark': {
          'primary' : '#059669',           /* Primary color */
          'primary-focus' : '#059669',     /* Primary color - focused */
          'primary-content' : '#ffffff',   /* Foreground content color to use on primary color */

          'secondary' : '#A7F3D0',         /* Secondary color */
          'secondary-focus' : '#A7F3D0',   /* Secondary color - focused */
          'secondary-content' : '#065F46', /* Foreground content color to use on secondary color */

          'accent' : '#37cdbe',            /* Accent color */
          'accent-focus' : '#2aa79b',      /* Accent color - focused */
          'accent-content' : '#FECACA',    /* Foreground content color to use on accent color */

          'neutral' : '#1F2937',           /* Neutral color */
          'neutral-focus' : '#111827',     /* Neutral color - focused */
          'neutral-content' : '#ffffff',   /* Foreground content color to use on neutral color */

          'base-100' : '#1F2937',          /* Base color of page, used for blank backgrounds */
          'base-200' : '#f9fafb',          /* Base color, a little darker */
          'base-300' : '#d1d5db',          /* Base color, even more darker */
          'base-content' : '#ffffff',      /* Foreground content color to use on base color */

          'info' : '#2094f3',              /* Info */
          'success' : '#009485',           /* Success */
          'warning' : '#FDE68A',           /* Warning */
          'error' : '#B91C1C',
        },
      },
    ]
  },
}
