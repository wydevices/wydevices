/*
 * Copyright (c) 2004-2009 Sergey Lyubka
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * $Id$
 * Unit test for the mongoose web server. Tests embedded API.

*/


#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include "mongoose.h"

#include <sys/types.h>
#include <unistd.h>

#if !defined(LISTENING_PORT)
#define LISTENING_PORT	"8080"
#endif /* !LISTENING_PORT */

static const char *standard_reply =	"HTTP/1.1 200 OK\r\n"
					"Content-Type: text/html\r\n"
					"Connection: close\r\n\r\n";

static void
test_error(struct mg_connection *conn, const struct mg_request_info *ri,
		void *user_data)
{


	mg_printf(conn, "HTTP/1.1 %d XX\r\n"
		"Conntection: close\r\n\r\n", ri->status_code);
	mg_printf(conn, "Error: [%d]", ri->status_code);
}

//Options defined: Array with recognized labels from URL

char *OptionsDefined[] = {
"element_1_1=1",
"element_1_2=1",
"element_1_3=1",
"element_1_4=1",
"element_2_1=1",
"element_2_2=1",
"element_2_3=1",
"element_2_4=1",
"element_3_1=1",
"element_3_2=1",
"element_3_3=1",
"element_3_4=1",
"element_4_1=1",
"element_4_2=1",
"element_4_3=1",
"element_4_4=1",
"element_5_1=1",
"element_5_2=1",
"element_5_3=1",
"element_5_4=1"
};


//Action Parser is a function to measure up if action is a recognized label from url, returning id, captured from options defined position
int ActionParser(char *s)
{
int i;
for (i=0; i < sizeof OptionsDefined/sizeof *OptionsDefined;
i++)
if (!strcmp(s, OptionsDefined[i]))
return i;
return -1;
}

//URL CallBack

static void
wymod_ri(struct mg_connection *conn, const struct mg_request_info *ri,
		void *user_data)
{
	char * pPairValues; 
	int j;
	int iAction;
	
	mg_printf(conn, "%s", standard_reply);  //Print headers
	mg_printf(conn, "<html><body><h1>Status</h1><br>");  //Start html tags
	mg_printf(conn, "%s",ri->query_string);  //Print query string (all URL after ?)

	mg_printf(conn, "\n <h2>Start pPairValues Loop</h2> \n");
	
	// Print out each Pair Value.

	pPairValues = strtok(ri->query_string,"&");

	j=0;

	while (pPairValues != NULL)      // While any Pair Value remains, measure up which action to take
	{
 
	//  mg_printf(conn, "\n DATA %d : %s \n",j,pPairValues);  // output data
	mg_printf(conn, "<table border=1>\n");  // output data

//Case/select/switch... iAction holds value returned by ActionParser. Depending on its value, takes an action. It consider the return value as the action required.

	    switch (iAction = ActionParser(pPairValues))  
	    {

	      case 0: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
		      system("extras enable transmission");
		      break;
	      case 1: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
		      system("extras start transmission");
		      break;
	      case 2: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras stop transmission");
	      	      break;
	      case 3: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras disable transmission");
	      	      break;
	      case 4: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras enable samba-server");
	      	      break;
	      case 5: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras start samba-server");
	      	      break;
	      case 6: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras stop samba-server");
	      	      break;
	      case 7: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras disable samba-server");
	      	      break;
	      case 8: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras enable samba-client");
	      	      break;
	      case 9: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras start samba-client");
	      	      break;
	      case 10: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras stop samba-client");
	      	      break;
	      case 11: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras disable samba-client");
	      	      break;
	      case 12: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras enable pure-ftpd");
	      	      break;
	      case 13: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras start pure-ftpd");
	      	      break;
	      case 14: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras stop pure-ftpd");
	      	      break;
	      case 15: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras disable pure-ftpd");
	      	      break;
	      case 16: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras enable dbupdater");
	      	      break;
	      case 17: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras start dbupdater");
	      	      break;
	      case 18: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras stop dbupdater");
	      	      break;
	      case 19: mg_printf(conn, "<tr><td>Action case %d with data: %s\n</td></tr>\n",iAction, pPairValues);
	      	      system("extras disable dbupdater");
	      	      break;
	
// default action, do nothing

	      default:
		      mg_printf(conn, "Action not recognized\n");
		      break;
	      }

	  mg_printf(conn, "</table>\n");  // output data
	  pPairValues=strtok(NULL,"&"); // Move pointer to next pair value
	  j++;
        }

	mg_printf(conn, "</table><a href=index.html>Back</a></body></html>");


}


//WyModEnd

int main(void)
{
	struct mg_context	*ctx; //moongoose context object


   pid_t pID = fork();  // Forking process

   if (pID == 0)                // child
   {
      // Hey! I have pid 0 so i think.... YES, I'm the child process ;-)

// Initialize ctx object, set listening port
	ctx = mg_start();
	mg_set_option(ctx, "ports", LISTENING_PORT);

// Create our wymod callback. I will use the mongoose context object to initilize, assign the pointer and that kind of stuff

	mg_set_uri_callback(ctx, "/wymod", &wymod_ri, NULL);

// create error handlers

	mg_set_error_callback(ctx, 404, &test_error, NULL);
	mg_set_error_callback(ctx, 0, &test_error, NULL);

	for (;;)	
{
	(void) getchar();
	sleep(5);
}		
    }
    else if (pID < 0)            // Negative value of pid? Something went wrong... fork failed.
    {
        exit(1);
        // Throw exception
    }
    else                                   // Hey, i'm not the child, i'm not a negative value, so i'm parent... Why don't bang my head and suicide... time to return command prompt and let my child to work for me...
    {
        exit(0);
    }
}

