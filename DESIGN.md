---
name: DealBD Community Deal Desk
description: A practical, trustworthy interface for evaluating and sharing deals in Bangladesh.
colors:
  brand-red: "#e82e31"
  brand-red-dark: "#c61f25"
  brand-red-soft: "#fff0f0"
  desk-charcoal: "#181b20"
  desk-slate: "#343942"
  muted-gray: "#68717d"
  workspace: "#f5f6f7"
  surface-white: "#ffffff"
  divider-gray: "#d9dde2"
  positive-green: "#157347"
  warning-amber: "#8a5a00"
  danger-red: "#b4232d"
typography:
  display:
    fontFamily: "Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "34px"
    fontWeight: 800
    lineHeight: 1.12
    letterSpacing: "-0.035em"
  headline:
    fontFamily: "Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "32px"
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: "-0.03em"
  title:
    fontFamily: "Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "20px"
    fontWeight: 800
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "15px"
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: "0"
  label:
    fontFamily: "Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "13px"
    fontWeight: 700
    lineHeight: 1.3
    letterSpacing: "0"
rounded:
  sm: "6px"
  control: "8px"
  surface: "12px"
  feature: "14px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.brand-red}"
    textColor: "{colors.surface-white}"
    rounded: "{rounded.control}"
    padding: "10px 16px"
    height: "42px"
  button-secondary:
    backgroundColor: "{colors.surface-white}"
    textColor: "{colors.desk-charcoal}"
    rounded: "{rounded.control}"
    padding: "10px 16px"
    height: "42px"
  field:
    backgroundColor: "{colors.surface-white}"
    textColor: "{colors.desk-charcoal}"
    rounded: "{rounded.control}"
    padding: "10px 11px"
    height: "43px"
---

# Design System: DealBD Community Deal Desk

## Overview

**Creative North Star: "Community Deal Desk"**

DealBD feels like an established public desk where Bangladeshi shoppers compare evidence before acting. It is practical, accountable, information-rich, and easy to operate. The interface prioritizes price, merchant, conditions, community score, discussion, disclosure, and moderation status.

Brand red works as a signal rather than decoration. Charcoal creates authority, white carries content, and cool gray separates information. The signature pattern is the **decision lane**: community confidence, deal evidence, and the next valid action remain aligned across feed, detail, and operational screens.

**Key Characteristics:**

- Compact, structured, and information-rich without becoming cramped.
- One clearly dominant action per task region.
- Strong deal-state and community-state visibility.
- Borders, alignment, and restrained fills instead of routine shadows.
- Familiar controls with direct, locally relevant language.

## Colors

The palette uses one brand signal, strong working neutrals, and semantic colors only when meaning is explicit.

### Primary

- **DealBD Red:** Brand identity, primary actions, current navigation, and important links.
- **Deep DealBD Red:** Hover and pressed primary-action state.
- **Signal Wash:** Quiet brand context for contribution prompts.

### Secondary

- **Positive Green:** Positive community scores, published states, success feedback, and genuine savings.
- **Warning Amber:** Pending review and caution states.
- **Danger Red:** Rejected, expired, destructive, and error states.

### Neutral

- **Desk Charcoal:** Primary copy, prices, and high-confidence information.
- **Desk Slate:** Secondary dark copy and control labels.
- **Muted Gray:** Metadata and supporting explanation.
- **Workspace:** Application background.
- **Surface White:** Working surfaces and controls.
- **Divider Gray:** Structural rules and borders.

**The Semantic Color Rule.** Brand red never substitutes for danger, and green never appears as decoration.

## Typography

**Display Font:** Inter with system UI fallbacks  
**Body Font:** Inter with system UI fallbacks

**Character:** One practical sans-serif family supports dense product work. Fixed sizes, weight, and spacing create hierarchy without promotional scale changes.

### Hierarchy

- **Display** (800, 34px, 1.12): Discovery introduction only.
- **Headline** (800, 32px, 1.15): Page titles.
- **Title** (800, 20px, 1.25): Section and panel headings.
- **Body** (400, 15px, 1.5): Interface copy and explanations, with long prose kept near 70 characters.
- **Label** (700, 13px, 1.3): Field labels, controls, and compact navigation.

Prices, scores, counts, and data columns use tabular numerals. Page headings carry their own hierarchy; decorative eyebrow labels are not part of the system.

## Layout

The shell is 1260px wide with 24px desktop gutters. Discovery and detail pages use a fluid primary lane plus a 264?300px supporting rail. Operational pages use full-width tables and compact horizontal task navigation.

At 820px, supporting rails collapse below primary content and navigation gains a scroll-safe second row. At 580px, forms and fact groups become single-column, deal rows preserve the score lane, and table regions remain horizontally scrollable when columns cannot collapse honestly.

Related facts stay close; major task regions receive 24?32px separation. Filters always sit above the content they scope.

## Elevation & Depth

The system is flat by default. Borders, alternate neutral fills, and alignment establish hierarchy. Routine cards, panels, tables, and forms do not use shadows. Focus uses an accessible outline rather than decorative glow.

**The Borders Before Elevation Rule.** A surface earns elevation only when it actually moves above another interaction layer; ordinary page regions remain grounded.

## Shapes

Controls use practical 8px corners, compact labels use 6px, and working surfaces use 12px. Feature groupings may reach 14px. Ordinary buttons and navigation are never pills. Borders remain one pixel and neutral unless they communicate semantic state.

## Components

### Buttons

- **Shape:** Compact rounded rectangle, 42px minimum height, 8px radius.
- **Primary:** DealBD Red fill, white copy, direct verb label.
- **Hover / Focus:** Darker red hover, one-pixel active movement, and a high-contrast focus outline.
- **Secondary:** White surface with a strong neutral border.
- **Destructive:** Pale danger surface, danger copy, and explicit destructive wording.

### Chips

Status and category labels use 6px corners, 11px bold copy, and explicit text. Pending is amber, published is green, rejected and expired are danger red, and neutral categories are gray.

### Cards / Containers

Primary working surfaces use white backgrounds, 12px corners, one-pixel gray borders, and no resting shadow. Deal rows are structured information surfaces rather than promotional cards.

### Inputs / Fields

Fields use a white fill, strong neutral border, 8px corners, sentence-case labels, and a dark accessible focus outline. Validation messages sit beside their fields while the page-level summary remains available.

### Navigation

The application bar is white with a four-pixel DealBD Red signal edge. Current primary navigation receives a red underline. Authenticated sections use compact horizontal task navigation that safely scrolls on small screens.

### Deal Decision Lane

A green score lane anchors each feed row. Title, merchant, conditions, and price form the central reading lane. A secondary details action occupies the final desktop column and disappears before core information is compressed.

## Do's and Don'ts

### Do:

- **Do** make price, title, merchant, status, and primary action readable first.
- **Do** expose moderation, affiliation, expiry, and manually submitted price-history limitations.
- **Do** use authored SVG icons or clear text labels.
- **Do** give controls default, hover, focus-visible, active, disabled, error, and empty states.
- **Do** explain what an empty state means and offer a valid next action.

### Don't:

- **Don't** use oversized promotional heroes inside the product.
- **Don't** use gradients, glows, glass effects, or decorative motion.
- **Don't** force every task into repeating same-size cards.
- **Don't** give primary, secondary, destructive, and administrative actions equal emphasis.
- **Don't** use Unicode glyphs as interface icons or decorative eyebrow labels above headings.
