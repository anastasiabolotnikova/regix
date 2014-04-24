package ee.ut.mt.webphp.tests;

import junit.framework.Test;
import junit.framework.TestSuite;

public class RegixTests {

	public static Test suite() {
		TestSuite suite = new TestSuite();
		suite.addTestSuite(MyPlanFirefoxTest.class);
		//suite.addTestSuite(MyPlanChromeTest.class);
		suite.addTestSuite(RegistrationFirefoxTest.class);
		suite.addTestSuite(RegistrationChromeTest.class);
		suite.addTestSuite(SignInFirefoxTest.class);
		suite.addTestSuite(SignInChromeTest.class);
		suite.addTestSuite(CalendarFirefoxTest.class);
		suite.addTestSuite(CalendarChromeTest.class);
		suite.addTestSuite(EventManagerFirefoxTest.class);
		suite.addTestSuite(EventManagerChromeTest.class);
		suite.addTestSuite(PermissionManagerFirefoxTest.class);
		suite.addTestSuite(PermissionManagerChromeTest.class);
		return suite;
	}

	public static void main(String[] args) {
		junit.textui.TestRunner.run(suite());
	}
}