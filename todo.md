# TODO
## pre-commit
- save branch and date
- imprint
## bugs
- form textarea jumps to end
    - solved by order
## css selectors

## logics
- reset confirm if something to reset
- wait for request return before class change
## layout / css
### bottom menu
- centered
- elements
    - fixed with
    - flex-grow: 0

### confirm
### new buttons
- buttons menu right when landscape?
- home button is not intuitive: go back to _back_ and _up_
## js
- Form.clear()

### good links
- themes
    - [media orientation](https://developer.mozilla.org/de/docs/Web/CSS/@media/orientation)
    - [CSS Dark Mode](https://www.mediaevent.de/css/darkmode.html)
    - [CSS dark mode invert](https://www.rechnerhaus.de/blog/css-dark-mode-mit-zwei-zeilen-code)

## data

## new login concept

## general
- shell export function

## encryption
### sign in new user
- start session after sign in
    - $uid
    - $pwd

### regular page
- check if password required
``usr()->isEncrypted()?``
- if so
    - session_start
    - $_SESSION['uid'] must match
    - $_SESSION['key'] must exist
    - OK
        - usr()->setPwd()
    - NOK
        - -> login

## login php
