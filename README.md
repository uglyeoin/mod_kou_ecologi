# KOU Ecologi

A Joomla 5 / 6 site module that shows your [Ecologi](https://ecologi.com)
impact: trees planted, carbon offset, carbon removed and habitat restored.

KOU Ecologi is a Bronze add-on from [Kind of Useful](https://kindofuseful.com).

## What it does

- **No API key.** Reads your public profile from Ecologi's public reporting API
  (`https://public.ecologi.com/users/{username}/impact`). Enter your username or
  paste your profile address.
- **Four figures:** trees, tonnes of CO₂e offset, tonnes of CO₂e removed and m²
  of habitat restored. Choose which to show, hide the ones still at zero, and
  optionally include impact that is paid for but not yet delivered.
- **Three layouts:** counter cards, a compact badge for footers and sidebars,
  or a plain sentence ("Our impact with Ecologi so far: 1,266 trees planted
  and 105.77 tonnes of CO₂e offset.").
- **Fast and sturdy.** Figures are cached (every 6 hours by default). If Ecologi
  can't be reached the last figures are shown and it tries again a few minutes
  later. No request is made from the visitor's browser.
- **Accessible.** Real text in a list, decorative icons hidden from screen
  readers, "opens in a new tab" announced. The optional count-up animation is
  skipped for visitors who prefer reduced motion.
- **Links to your forest**, with your referral code if you have one.
- Pale green, white or no background, with your own accent colour.
- If the username is missing or wrong, visitors see nothing and people who can
  manage modules see a short message saying why.

## Settings

| Setting | What it does |
| --- | --- |
| Ecologi username | `yourname` from `ecologi.com/yourname`, or the whole address. The profile must be public. |
| Show trees / carbon offset / carbon removed / habitat | Which figures to show. |
| Hide figures that are zero | On by default. |
| Include pending impact | Adds impact Ecologi has not yet delivered. |
| Intro text, link text, referral code, new tab | Wording and link. |
| Style | Background, accent colour, alignment, icons, count-up. |
| Layout (Advanced) | Counter cards, Badge or Sentence. |
| Check Ecologi every (hours) | 1 to 168, default 6. |

## Requirements

- Joomla 5.0 or later (built and tested on Joomla 6)
- PHP 8.1 or later
- A public Ecologi profile

## Install

Extensions → Install → upload `mod_kou_ecologi.zip`, then Content → Site
Modules → New → KOU Ecologi.

Upgrading from 0.0.x keeps your module: the username and the trees / carbon
choice are carried across, and the old language files are removed.

To receive updates through Joomla's updater, enter your Kind of Useful
**Download ID** in System → Update Sites → KOU Ecologi.

## Licence

GNU General Public License version 2 or later. Ecologi is a trademark of its
owner; this module is not made or endorsed by Ecologi.
