
package zaompp;

import java.applet.Applet;
import java.io.IOException;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.awt.datatransfer.*;
import java.awt.*;
import java.security.*;

/**
 *
 * @author igor
 */
public class bazaApplet extends Applet implements ClipboardOwner {

    public void logme(Object... objs) {
        String text = (String) objs[0];
        if (objs.length==1)
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, text);
        else
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, text, objs[1]);
    }
    
    public String addFile() {
        Frame frame = new Frame (  ) ;
        FileDialog fd = new FileDialog(frame);
        fd.setVisible(true);
        String filename = fd.getDirectory()+fd.getFile();
        this.logme(filename);
        return filename;
    }

    public String openfile(String file) {
        
       String cmd = null;
       cmd = getParameter("cmd");

       this.logme("Start execution");
        try
        {
            cmd = getParameter("cmd");
            this.logme( "args value : = {0}", file);
            this.logme( "cmd value : = {0}", cmd);
            this.logme( "Full command:  = {0} {1}", new Object[]{cmd, file});
            if (cmd != null && !cmd.trim().equals(""))
            {
                if (file == null || file.trim().equals(""))
                {
                    this.logme("One");
                    final String tempcmd = cmd;
                    Process process = (Process) AccessController.doPrivileged(new PrivilegedAction() {
                        public Object run() {
                            Process p = null;
                            try
                            {
                                p = Runtime.getRuntime().exec(tempcmd);
                            }
                            catch (Exception e)
                            {
                                zaompp.bazaApplet.this.logme( "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p; 
                        }
                    });
                    BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    while((s = bufferedReader.readLine()) != null)
                        this.logme(s);
                }
                else
                {
                    this.logme("Two");
                    final String tempargs = file;
                    final String tempcmd1 = cmd;
                    Process process = (Process) AccessController.doPrivileged(new PrivilegedAction() {
                        public Object run()
                        {
                            Process p = null;
                            try
                            {
                                p = Runtime.getRuntime().exec(tempcmd1 + " " + tempargs);
                                //p.wait();
                            }
                            catch (Exception e)
                            {
                                zaompp.bazaApplet.this.logme( "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p;
                        }
                    });
                    /*BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    while((s = bufferedReader.readLine()) != null)
                        this.logme(s);
                     */
                }
            }
            else
            {
                this.logme("execCmd parameter is null or empty");
            }
        }
        catch (Exception e)
        {
            this.logme( "Error executing command --> {0} ({1})", new Object[]{cmd, file});
            this.logme( null, e);
        }
        return "Fuck 111 " + file;

    }

    public boolean copytoclipboard(String text) {
        SecurityManager sm = System.getSecurityManager();
        if (sm != null) {
            try {
               sm.checkSystemClipboardAccess();
            }
            catch (Exception ex) {
                this.logme( null, ex);
            }
        } else {
            this.logme( "Can't get SecurityManager");
        }
        final Toolkit tk = Toolkit.getDefaultToolkit();
        StringSelection st = new StringSelection(text);
        Clipboard cp = (Clipboard) AccessController.doPrivileged(new PrivilegedAction() {
                public Object run() {
                    return tk.getSystemClipboard();
                }
            });
            //tk.getSystemClipboard();
        cp.setContents(st,this);
        return true;
    }
    public void lostOwnership(Clipboard clip, Transferable tr) {
       this.logme("Lost Clipboard Ownership?!?");
    }
}