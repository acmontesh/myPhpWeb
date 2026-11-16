Fixes, part 1:
- The carrousel should occupy the width of the page, as it is right now, but not all the height. Adjust the height. 
- In the about-me section, there should be a "Education", "professional experience", and "my research interests" subsections, all of which should be editable from the panel. I expect the bullet points to reflect with very good looking icons from font-awesome. 
- The contact-me form does not look good. It looks too simple. Make it very good looking. Also, it is not centered. 
- The footer and the menu should not be white. Change the background. 
- I don't like the fonts. Try different fonts. Titles should be different fonts, and ideally in burnt orange (evaluate this).
- A button to contact me in the page header and also links to my social networks. 
- The menu should be good looking. Right now it's plain. The visitor cannot select dark/light modes. Also, the menu should stick to the bottom when the visitor scrolls down. Animate the menu as well, so that there is hover reaction, as in the current desing. 
- The menu should also be collapsable and expandable in mobile view. The "X" or three bars to open the menu should also be animated (check the current design).

Fixes, part 2: 
- The carrousel is not automatically advancing. When it gets to the end, it does not automatically advance to the first quote. 
- The carrousel does not have a title. Add a title that says "quotes that I like".
- For background image on the header, also implement a carrousel, but that automatically advances without any button for the visitor to control. Use the images in the carrousel-header folder inside laravel-app. 

Fixes, part 3: 
- The name "Abraham C. Montes" that is in front of the header is orange, and it's seating on an orange background, so it is not visible. Change the color of this text. 
- The title "Quotes that I like" should be "Quotes I like". 
- Titles in general should be centered rather than aligned to the left. 

Fixes, part 4:
- I get an error when uploading the PDF of a paper because the file is too large (16 MB). Enable files up to 50 MB, as some articles are quite large. 
- The carousel in the header has a minor bug. Images start zooming quite nicely, but before it changes to the next image, it jumps. The very last part of the transition/animation is abrupt. Make it smooth. 

Fixes, part 5:
- Add a new section that lists awards/distinctions (please select the appropriate title). Each award should display the name of the awards, who or what institution granted it, the date, and a short description. Maybe an image/picture associated with it. Create the table as required in the DB as well. These awards should have their CRUD in the admin panel. 
- Blog posts should allow embedding pieces of code with the proper formatting (distinguished from the main text) in the middle of the text.  

Fixes, part 6:
- The social network buttons that currently are in the menu should not be on the menu, but rather in the header below my name and the text below it. 
- Blog posts in the public part should have a fashionable, good looking button that says "read more". 

Fixes, part 7:
- Words in the menu are aligned to the left. In mobile view, that makes sense. However, in large-screen view, they should be centered. 
  
Fixes, part 8:
- The header carousel should occupy the whole height of the page, even in landing mode. It should have an animated arrow under my name and my subtitle that indicates the user that more content is available when scrolling down. Make this arrow very good looking. 

Fixes, part 9:
- The alpha of the orange filling in the header hero carousel should be customizable on the admin panel. Additionally, the picture in the panel, along with the social network buttons below the subtitle, and the arrow poiting down should be a bit larger. Please modify it accordingly. 