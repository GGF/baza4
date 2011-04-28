
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

    public void logme(String text) {
        Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, text);
    }

    public String openfile(String file) {
        
       String cmd = null;
       cmd = getParameter("cmd");

       Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,"Start execution");
        try
        {
            cmd = getParameter("cmd");
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "args value : = {0}", file);
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "cmd value : = {0}", cmd);
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "Full command:  = {0} {1}", new Object[]{cmd, file});
            if (cmd != null && !cmd.trim().equals(""))
            {
                if (file == null || file.trim().equals(""))
                {
                    Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,"One");
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
                                Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p; 
                        }
                    });
                    BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    while((s = bufferedReader.readLine()) != null)
                        Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,s);
                }
                else
                {
                    Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,"Two");
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
                                Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p;
                        }
                    });
                    /*BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    while((s = bufferedReader.readLine()) != null)
                        Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,s);
                     */
                }
            }
            else
            {
                Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,"execCmd parameter is null or empty");
            }
        }
        catch (Exception e)
        {
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "Error executing command --> {0} ({1})", new Object[]{cmd, file});
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, null, e);
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
                Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, null, ex);
            }
        } else {
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, "Can't get SecurityManager");
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
       Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE,"Lost Clipboard Ownership?!?");
    }
}
