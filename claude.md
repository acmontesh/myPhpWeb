# Project: Abraham Montes Website

---

## Agent's role: 
Full stack web developer with 12 years of experience. 

---

## Objective: 
Transform the current website written in PHP into a Laravel-based website/application with a modern and fashionable design, inspired in the current design but with notable improvements, with the following features:
- Have admin capabilities. 
- Choose several visual themes. 
- Have a blog. 
- Have an admin panel. 
- Manage public information about the web. 
- Display quotes I like.
- Display news. 
- Potential interested people in contacting me can reach out through a contact form. 
- Display the papers I have written.

The objective is to display my academic portfolio, news, achievements, and personal preferences like the quotes I like. 

---

## General requirements: 
These rules apply always to all phases and functionalities:
- Routes protection. 
- Prioritize simplicity, intuitiveness, and ease of understanding. 
- Prioritize security and good practices. 
- Show confirmation messages. 
- If there's no data in a particular section, display a kind/fashionable "empty state". 
- Use font Awesome (the font is in the "view-theme-base/assets/fonts").
- All actions in the dashboard (admin panel) require authentication. 
- All URLs in the admin panel must start with: /panel-abraham
- All functionalities must be totally functional (they must work). 
- Prior functionalities (from previous phases) must not be broken. 
- Maintain visual consistency. 
- Maintain responsive coherence. 
- Prioritize UX. 
- Prioritize component reutilization. 
- Validate genuine human-requested contact.
- Language selection: english or spanish.

---

## General architecture: 

### Public part: 
This public part will include: 
- Homepage
- About me
- News
- Research
- Publications
- Blog
- Quotes I Like
- Contact Me

### Private dashboard: 
The dashboard will allow: 
- Manage blog
- Manage research projects
- Manage publications
- Manage news
- Manage visual themes
- Manage images and icons
- Manage web settings
- Manage public sentences (hard-coded sentences)
- View notifications
- Manage email notifications
- Manage social networks
- Manage private profile
  
---

## App's functionalities: 

### Phase 1: 
- Private admin panel :
  - Secure log in with email, phone number, and password. 
  - Mandatory to provide the three things, and there should be an option to persist the log-in, so the engineer doesn't need to fill in the form every time. 
  - Use the best method for the log in and secure authentication, but not overly complex. Password must be well encrypted. 
  - Login at the URL: /access-abraham

### Phase 2: 
- Dashboard at the URL: /panel-abraham. All URLs in the dashboard will part from this and all of them require authentication, same as any action performed within the dashboard. 
- Structured layout and dashboard menu. 
- Dashboard must be intuitive, with a lateral menu on the left. Certain elements of the menu must be "dropdown" (same as wordpress for grouping things that make sense are grouped). Most important actions for the engineer must have a "quick-acess" element in the menu. 

### Phase 3: 
- Within the admin panel: 
  - Manage blog with its CRUD for each article. Each article should allow uploading an image. 
  - All large text fields in the dashboard should have a text editor wysiwg so that edition is a good experience (e.g., https://xdsoft.net/jodit/)
  - Copy the library in the project, download from CDN. Installation info and tool's usage is here: https://xdsoft.net/jodit/docs/docs/getting-started.html 
  - CDN to download the wysiwig is: https://cdnjs.cloudflare.com/ajax/libs/jodit/4.7.6/es2021/jodit.min.css and https://cdnjs.cloudflare.com/ajax/libs/jodit/4.7.6/es2021/jodit.min.js
  - Blog articles can be shared on social media. Add buttons so that the reader can do that. 
  - Each article must display date of publication. 

### Phase 4:
- Within the admin panel: 
  - Manage quotes I like.
  - Each quote should allow uploading a picture, so it is displayed in the carousel. 
  - Redesign the carousel so it is more fashionable, occupies the entire page, with the face of the author on one side, and a fading toward a solid color on which the quote is diplayed. The carousel should pass the quotes automatically, but also allow the visitors to go back to a particular quote. It should loop over the existing quotes.  

### Phase 5:
- Within the admin panel: 
  - Manage research projects with its CRUD. Each research project must have a text associated with it and a picture. 
  - Visitors interested in reading more can click on a dropdown to display more information. 
    

### Phase 6:
- Manage publications in the admin panel with its CRUD. 
- Each publication may be journal or conference, and can have an associated image. Be inspired in the website of https://www.robertavaltorta.com/publications
- I can upload a PDF of the article to share. Visitors will be able to download the PDF if it is available. 
- Articles can have an associated video explaining the highlights of the project. If so, the public part will display a button to allow visitors to see the video.
- Articles will have information about the citations. If possible, make this number of citations automated, getting the information from Google Scholar. The primary key could be the article's DOI.  

### Phase 7:
- Within the admin panel: 
  - Configuration of all information that is displayed publicly.
  - Manage news. Create a CRUD, with two images to display. 

### Phase 8: 
- Within the admin panel: 
  - Managament of themes with 5 different templates: The engineer will be able to select the theme and it will be activated immediately to change the web's appearance. 
  - Themes should be selected in landing or multipage formats. 
  - When clicking on the theme, there should be a preview of the theme on a modal window. 
  - Templates shoould be stored in a folder "themes" so they can be modified easily and plug-and-play. 

### Phase 9: 
- Within the admin panel: 
  - Management of images that appear in the public part. There should be slots in the themes that will use test images.

### Phase 10: 
- Within the admin panel: 
  - Option of dark theme or light theme. When one of the two is selected, it should remain even if the engineer logs out. 
  - When selecting dark or light mode, there should be a modal window with 8 possible colors so that the user can select (one will be blue on the dashboard) to act as the main color. 
- All sections in the web can be activated or deactivated (blog, about me, etc.)

### Phase 11: 
- Within the admin panel: 
  - Create a section to manage "public phrases", where all strings or sentences that appear by default can be customized. 
  - Section to configure social networks, where the engineer can indicate links to her social networks. 
  - Section "email and notifications". 
  - In the header, the engineer can click on her name/avatar, which allows changing the coordinator's private information. 
  - Add a button on the lateral menu to go to the public part. 

### Phase 12: 
- In the public part: 
  - Information about me. 
  - If the engineer selects "landing mode", the entire web will be on a single page with smooth scrolling. If not, the web will have differentiated sections. 
    - Index with a landing page. 
    - Section about me - in another URL. 
    - Section news - in another URL. 
    - Section blog - in another URL.  
    - Section research - in another URL.  
    - Section publications - in another URL.  
    - Section quotes I like - in another URL.  
    - Section contact me - in another URL.  
  - Even in landing mode, news should be headlines and a picture. If visitors are insterested in additional details, they can click to see another page with the entire news. Get inspired in the current design of the website, but improve it to be a lot more fashinable and good looking. 

### Phase 13:
- In the public part: 
  - Clients can select the language.
  - When sending a contact-me request, add human-vs-robot validation, for example using Google reCaptcha. Make sure the style of the Captcha fits within the selected theme. 

---

## Technology stack: 
- HTML5
- CSS3 (native)
- JavaScript (native, no frameworks)
- Backend programming language: PHP
- PHP framework: Laravel
- Database: MySQL
- Monolithic web application with Laravel-based architecture

---

## General preferences:
- All visible texts must be written in english, but the web must have an option to move into spanish.
- The agent must communicate with me in english. 
- Use the images currently in this project. Later, these images can be edited or changed in a section of the dashboard. 

---

## Design preferences for the public part: 
- Be based on the current design, but propose changes so it looks modern, very good looking, fashionable, clear, and attractive for an audience of AI/ML experts, drilling engineers and computer scientists.
- Take inspiration in the websites https://www.robertavaltorta.com/ and https://madeline-eppley.com. If you cannot access the website, do not keep trying indefinitely.

---

## Design preferences for the private dashboard: 
- Create a fashionable dashboard, very good looking, easy to understand, very intuitive for an engineer. 

---

## Style preferences: 
- Colors: Each visual theme can have different colors. Use as default white and burnt orange. Letters on the orange background must be white, so there is good contrast. 
- Measurements in REM, using a base font size of 11px. 
- Use HTML5 and CSS3 (native). 
- Use good CSS layout practices, and if necessary, use flexbox and grid layout. 
- The web app must be responsive, both the public part and the dashboard. 

---

## Coding preferences: 
- Do not mix CSS code in different components. Separate them for each visual theme and the dashboard. 
- HTML5 must be semanthic. 
- The public part must be completely optimized for SEO (code level and good practives).
- Always use let or const and never use var. 
- Do not use alert, confirm or prompt. All feedback must be visual in DOM. 
- Any alert or modal window must have the same web's style. 
- Do not use innerHTML. All contents must be inserted with appendChild or with document.createElement
- Do not forget to prevent default in submit or click events. 
- Prioritize understandable and maintainable code. 
- Prioritize code simplicity. 
- If the agent has a doubt, review the project specs and/or query the user. 

---

## File structure: 
- claude.md
- Folder for the Laravel project: Use the most adequate folder structure for Laravel projects. 

---

## Development phases: 
- Study the characteristics of the project. 
- Use the existing local database abrahammontes, but change it accordingly to reflect the modifications you will implement.
- Follow the development phases specified in the "App's functionalities" section.
- Pause working after every phase so that I can test what you have developed, correct, and improve if necessary before continuing to the next phase. 
- If you believe optimization is possible, please indicate so in the implementation plan.

---

## Other requirements: 
- Save the implementation plan in a file "implementation-plan.md" in the project's root. 
- Save tasks and their status in a file "tasks.md" in the project's root. Every time a task is completed, update this file. 
- Save each new prompt in a file "prompts.md" in the project's root (in order within the file). Each time a prompt is made, update this file. 
- 

---

## Implementation mode: 
- Only coding, minimum comments, the code must be intuitive. 
- Do not explain what the code does on the agent's chat. 
- Answer in english. 
- If there's ambiguity, make the simplest decision that does not break any functionality. 
